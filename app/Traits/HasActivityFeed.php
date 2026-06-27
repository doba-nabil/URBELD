<?php

namespace App\Traits;

use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Rating;

trait HasActivityFeed
{
    /**
     * Get the formatted activity feed.
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getActivityFeed($limit = 8)
    {
        $latestUsers = User::latest()->take($limit)->get()->map(function($user) {
            return [
                'id' => $user->id,
                'type' => 'user', 
                'type_label' => __('admin.new_user_registered'),
                'message' => $user->name, 
                'date' => $user->created_at,
                'user_name' => $user->name,
                'url' => route('users.show', $user->id)
            ];
        });

        $latestRequests = ServiceRequest::with('user')->latest()->take($limit)->get()->map(function($req) {
            return [
                'id' => $req->id,
                'type' => 'request', 
                'type_label' => __('admin.new_request_created'),
                'message' => $req->category->name ?? __('admin.service_request'), 
                'date' => $req->created_at,
                'user_name' => $req->user->name ?? '-',
                'url' => route('service-requests.show', $req->id)
            ];
        });

        $latestRatings = Rating::with(['rater', 'rated'])->latest()->take($limit)->get()->map(function($rating) {
            return [
                'id' => $rating->id,
                'type' => 'rating', 
                'type_label' => __('admin.rated'),
                'message' => ($rating->rater->name ?? '') . ' ' . __('admin.rated') . ' ' . ($rating->rated->name ?? '') . ' (' . $rating->rating . ' ★)', 
                'date' => $rating->created_at,
                'user_name' => $rating->rater->name ?? '-',
                'url' => $rating->service_request_id ? route('service-requests.show', $rating->service_request_id) : '#'
            ];
        });
        
        return collect()
            ->merge($latestUsers)
            ->merge($latestRequests)
            ->merge($latestRatings)
            ->sortByDesc('date')
            ->take($limit)
            ->values();
    }
}
