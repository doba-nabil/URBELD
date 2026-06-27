<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\MembershipCertificate;
use App\Models\User;
use App\Models\UserMembershipHistory;
use App\Traits\slugGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MembershipService
{
    use slugGenerator;

    public function getAll()
    {
        return Membership::ordered()->get();
    }

    public function getActive()
    {
        return Membership::active()->ordered()->get();
    }

    public function getById($id)
    {
        return Membership::findOrFail($id);
    }

    public function create(array $data, $files = [])
    {
        DB::beginTransaction();
        try {
            $membership = Membership::create($data);
            
            // Handle file uploads
            if (isset($files['id_front_image'])) {
                $membership->addMediaFromRequest('id_front_image')->toMediaCollection('id_front');
            }
            if (isset($files['id_back_image'])) {
                $membership->addMediaFromRequest('id_back_image')->toMediaCollection('id_back');
            }
            if (isset($files['commercial_registration'])) {
                $membership->addMediaFromRequest('commercial_registration')->toMediaCollection('commercial_registration');
            }
            
            // Sync sub categories (for both individual and company)
            if (isset($data['sub_categories'])) {
                $membership->subCategories()->sync($data['sub_categories'] ?? []);
            }
            
            // Handle certificates
            if (isset($data['certificates'])) {
                foreach ($data['certificates'] as $index => $certData) {
                    if (!empty($certData['name'])) {
                        $certificate = $membership->certificates()->create([
                            'name' => $certData['name'],
                            'sort_order' => $index,
                        ]);
                        
                        if (isset($files['certificates'][$index]['image'])) {
                            $certificate->addMediaFromRequest("certificates.{$index}.image")
                                ->toMediaCollection('certificate_image');
                        }
                    }
                }
            }
            
            DB::commit();
            return $membership;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Membership $membership, array $data, $files = [], ?User $user = null)
    {
        DB::beginTransaction();
        try {
            $membership->update($data);
            
            // Handle file uploads
            if (isset($files['id_front_image'])) {
                $file = $files['id_front_image'];
                $membership->clearMediaCollection('id_front');
                $membership->addMedia($file)
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->preservingOriginal()
                    ->toMediaCollection('id_front');
                if ($user) {
                    $user->clearMediaCollection('id_front');
                    $user->addMedia($file)
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('id_front');
                }
            }
            if (isset($files['id_back_image'])) {
                $file = $files['id_back_image'];
                $membership->clearMediaCollection('id_back');
                $membership->addMedia($file)
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->preservingOriginal()
                    ->toMediaCollection('id_back');
                if ($user) {
                    $user->clearMediaCollection('id_back');
                    $user->addMedia($file)
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('id_back');
                }
            }
            if (isset($files['commercial_registration'])) {
                $file = $files['commercial_registration'];
                $membership->clearMediaCollection('commercial_registration');
                $membership->addMedia($file)
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->preservingOriginal()
                    ->toMediaCollection('commercial_registration');
                if ($user) {
                    $user->clearMediaCollection('commercial_registration');
                    $user->addMedia($file)
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('commercial_registration');
                }
            }

            if (isset($files['personal_photo'])) {
                $file = $files['personal_photo'];
                if ($user) {
                    $user->clearMediaCollection('personal_photo');
                    $user->addMedia($file)
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->preservingOriginal()
                        ->toMediaCollection('personal_photo');
                    
                    // Also sync to 'users' collection if used as a backup
                    $user->clearMediaCollection('users');
                    $user->addMedia($file)
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('users');
                }
            }
            
            // Sync sub categories (for both individual and company)
            if (isset($data['sub_categories'])) {
                $membership->subCategories()->sync($data['sub_categories'] ?? []);
            } else {
                $membership->subCategories()->sync([]);
            }
            
            // Handle certificates
            if (isset($data['certificates'])) {
                $requestedCertIds = collect($data['certificates'])
                    ->pluck('id')
                    ->filter(function($id) {
                        return !empty($id) && !str_starts_with($id, 'media_');
                    })
                    ->values()
                    ->toArray();
                
                \Log::info('MembershipCert update debug:', [
                    'requested_cert_ids' => $requestedCertIds,
                    'all_raw_data' => $data['certificates'],
                ]);
                
                // Delete certificates not in the request
                $membership->certificates()->whereNotIn('id', $requestedCertIds)->delete();
                
                foreach ($data['certificates'] as $index => $certData) {
                    if (!empty($certData['name'])) {
                        $certId = isset($certData['id']) ? $certData['id'] : null;
                        $oldMediaId = null;
                        
                        // Check if it's an old User Media certificate (ID starts with 'media_')
                        if (!empty($certId) && str_starts_with($certId, 'media_')) {
                            $oldMediaId = str_replace('media_', '', $certId);
                            $certId = null; // Clear it so it creates a new MembershipCertificate
                        }

                        if (!empty($certId)) {
                            // Update existing
                            $certificate = MembershipCertificate::find($certId);
                            if ($certificate) {
                                $certificate->update([
                                    'name' => $certData['name'],
                                    'sort_order' => $index,
                                ]);
                            }
                        } else {
                            // Create new
                            $certificate = $membership->certificates()->create([
                                'name' => $certData['name'],
                                'sort_order' => $index,
                            ]);
                            
                            // If transitioning from old User Media, copy the old image if no new one is provided
                            if ($oldMediaId && $user) {
                                $oldMedia = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('id', $oldMediaId)
                                    ->where('model_id', $user->id)
                                    ->first();
                                    
                                if ($oldMedia && !isset($files['certificates'][$index]['image'])) {
                                    // Securely migrate the existing media to the new model directly
                                    // This guarantees no filesystem operations fail
                                    $oldMedia->update([
                                        'model_type' => get_class($certificate),
                                        'model_id' => $certificate->id,
                                        'collection_name' => 'certificate_image'
                                    ]);
                                } elseif ($oldMedia) {
                                    // If there's a new file provided, we can safely delete the old media
                                    $oldMedia->delete();
                                }
                            }
                        }
                        
                        if ($certificate && isset($files['certificates'][$index]['image'])) {
                            $file = $files['certificates'][$index]['image'];
                            // Add to certificate model
                            $certificate->clearMediaCollection('certificate_image');
                            $certificate->addMedia($file)
                                ->usingName($file->getClientOriginalName())
                                ->usingFileName($file->getClientOriginalName())
                                ->preservingOriginal()
                                ->toMediaCollection('certificate_image');
                            
                            // No longer add to User model certificates collection to prevent duplication
                            // since the front-end now loads and migrates both.
                        }
                    }
                }
            }

            // Handle works
            if (isset($data['works'])) {
                $requestedWorkIds = collect($data['works'])
                    ->pluck('id')
                    ->filter(function($id) { return !empty($id); })
                    ->values()
                    ->toArray();
                
                // Delete works not in the request
                if ($user) {
                    $user->works()->whereNotIn('id', $requestedWorkIds)->delete();
                }

                foreach ($data['works'] as $index => $workData) {
                    if (!empty($workData['title'])) {
                        $workId = isset($workData['id']) ? $workData['id'] : null;
                        $work = null;

                        if (!empty($workId)) {
                            // Update existing
                            $work = \App\Models\ProviderWork::find($workId);
                            if ($work) {
                                $work->update([
                                    'title' => $workData['title'],
                                    'description' => $workData['description'] ?? null,
                                    'sort_order' => $index,
                                ]);
                            }
                        } else {
                            // Create new
                            if ($user) {
                                $work = $user->works()->create([
                                    'title' => $workData['title'],
                                    'description' => $workData['description'] ?? null,
                                    'sort_order' => $index,
                                ]);
                            }
                        }
                        
                        // Handle multiple Work images
                        if ($work && isset($files['works'][$index]['images'])) {
                            foreach ($files['works'][$index]['images'] as $file) {
                                $work->addMedia($file)
                                    ->usingName($file->getClientOriginalName())
                                    ->usingFileName($file->getClientOriginalName())
                                    ->preservingOriginal()
                                    ->toMediaCollection('work_images');
                            }
                        }
                    }
                }
            } else {
                if ($user) {
                    $user->works()->delete();
                }
            }
            
            DB::commit();
            return $membership;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $membership = Membership::findOrFail($id);
        return $membership->delete();
    }

    /**
     * ربط المستخدم بعضوية
     */
    public function assignToUser(User $user, Membership $membership, $pricePaid = null)
    {
        // تحديث العضوية الحالية
        $user->update([
            'membership_id' => $membership->id,
        ]);

        return $user;
    }

}
