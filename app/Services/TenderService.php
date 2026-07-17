<?php

namespace App\Services;

use App\Models\Tender;
use App\Models\TenderApplication;
use App\Models\SavedTender;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TenderService
{
    /**
     * Get filtered and paginated active tenders
     */
    public function getFilteredTenders(Request $request)
    {
        $tab = $request->get('tab', 'all');
        $query = Tender::with(['category', 'city']);

        if ($tab === 'closed') {
            $query->where(function($q) {
                $q->whereIn('status', [Tender::STATUS_CLOSED, Tender::STATUS_COMPLETED])
                  ->orWhere(function($subq) {
                      $subq->where('status', Tender::STATUS_ACTIVE)
                           ->whereNotNull('ends_at')
                           ->where('ends_at', '<=', now());
                  });
            });
        } elseif ($tab === 'open') {
            $query->active(); // Active means status = active and ends_at > now
        } elseif ($tab === 'urgent') {
            $query->active()->where('is_urgent', true);
        } else {
            // 'all'
            $query->where(function($q) {
                $q->whereIn('status', [Tender::STATUS_ACTIVE, Tender::STATUS_CLOSED, Tender::STATUS_COMPLETED]);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $sort = $request->get('sort', 'latest');
        if ($sort === 'latest') {
            $query->latest();
        } elseif ($sort === 'budget_high') {
            $query->orderBy('budget', 'desc');
        } elseif ($sort === 'ending_soon') {
            $query->orderBy('ends_at', 'asc');
        }

        return $query->paginate(12)->appends($request->all());
    }

    /**
     * Get statistics for tabs
     */
    public function getStats(Request $request = null)
    {
        $applyFilters = function ($q) use ($request) {
            if ($request) {
                if ($request->filled('category_id')) {
                    $q->where('category_id', $request->category_id);
                }
                if ($request->filled('city_id')) {
                    $q->where('city_id', $request->city_id);
                }
                if ($request->filled('keyword')) {
                    $keyword = $request->keyword;
                    $q->where(function ($sub) use ($keyword) {
                        $sub->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
                }
            }
            return $q;
        };

        $allQuery = Tender::whereIn('status', [Tender::STATUS_ACTIVE, Tender::STATUS_CLOSED, Tender::STATUS_COMPLETED]);
        $allQuery = $applyFilters($allQuery);

        $openQuery = Tender::active();
        $openQuery = $applyFilters($openQuery);

        $urgentQuery = Tender::active()->where('is_urgent', true);
        $urgentQuery = $applyFilters($urgentQuery);

        $closedQuery = Tender::where(function($q) {
            $q->whereIn('status', [Tender::STATUS_CLOSED, Tender::STATUS_COMPLETED])
              ->orWhere(function($subq) {
                  $subq->where('status', Tender::STATUS_ACTIVE)
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '<=', now());
              });
        });
        $closedQuery = $applyFilters($closedQuery);

        return [
            'all' => $allQuery->count(),
            'open' => $openQuery->count(),
            'urgent' => $urgentQuery->count(),
            'closed' => $closedQuery->count(),
        ];
    }

    /**
     * Create a new tender with associated files
     */
    public function createTender(User $user, array $data, $files = null, $fileTitles = null): Tender
    {
        DB::beginTransaction();
        try {
            $tender = Tender::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'project_type' => $data['project_type'] ?? null,
                'category_id' => $data['category_id'],
                'city_id' => $data['city_id'],
                'budget' => $data['budget'] ?? null,
                'qualification_requirements' => $data['qualification_requirements'] ?? null,
                'ends_at' => $data['ends_at'],
                'is_urgent' => isset($data['is_urgent']) ? (bool) $data['is_urgent'] : false,
                'status' => Tender::STATUS_PENDING_REVIEW,
            ]);

            $tender->request_key = 'TEN-' . date('Ymd') . '-' . str_pad($tender->id, 4, '0', STR_PAD_LEFT);
            $tender->saveQuietly();

            if ($files) {
                foreach ($files as $index => $file) {
                    $title = $fileTitles[$index] ?? $file->getClientOriginalName();
                    $tender->addMedia($file)
                           ->withCustomProperties(['title' => $title, 'file_type' => 'file'])
                           ->toMediaCollection('tender_files');
                }
            }

            // Notify admins
            $admins = User::where('is_admin', true)->get();
            if ($admins->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewTenderAdminNotification($tender));
            }

            DB::commit();
            return $tender;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Submit an application for a tender
     */
    public function applyToTender(Tender $tender, User $user, array $data, $files = null, $fileTitles = null): TenderApplication
    {
        if ($tender->isExpired()) {
            throw new \Exception('انتهى وقت التقديم على هذه المناقصة.');
        }

        DB::beginTransaction();
        try {
            $application = TenderApplication::create([
                'tender_id' => $tender->id,
                'user_id' => $user->id,
                'price' => $data['price'] ?? null,
                'delivery_days' => $data['delivery_days'] ?? null,
                'notes' => $data['notes'],
            ]);

            if ($files) {
                foreach ($files as $index => $file) {
                    $title = $fileTitles[$index] ?? $file->getClientOriginalName();
                    $application->addMedia($file)
                                ->withCustomProperties(['title' => $title, 'file_type' => 'file'])
                                ->toMediaCollection('application_files');
                }
            }

            DB::commit();
            
            // Notify tender owner
            if ($tender->user) {
                $tender->user->notify(new \App\Notifications\GenericNotification(
                    __('tenders.new_offer_title'),
                    __('tenders.new_offer_desc', ['user' => $user->name, 'tender' => $tender->title]),
                    route('website.tenders.show', $tender->id),
                    ['tender_id' => $tender->id, 'application_id' => $application->id]
                ));
            }

            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Toggle bookmark/save status for a tender
     */
    public function toggleSaveTender(Tender $tender, User $user): array
    {
        $saved = SavedTender::where('user_id', $user->id)->where('tender_id', $tender->id)->first();

        if ($saved) {
            $saved->delete();
            return ['status' => 'removed', 'message' => 'تم إزالة المناقصة من المحفوظات'];
        } else {
            SavedTender::create([
                'user_id' => $user->id,
                'tender_id' => $tender->id,
            ]);
            return ['status' => 'saved', 'message' => 'تم حفظ المناقصة بنجاح'];
        }
    }
}
