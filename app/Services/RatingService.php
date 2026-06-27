<?php

namespace App\Services;

use App\Models\Rating;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RatingService
{
    public function getById($id)
    {
        return Rating::with(['serviceRequest', 'rater', 'rated'])->findOrFail($id);
    }

    /**
     * إنشاء تقييم متبادل (تقييمين: من طالب الخدمة لمقدم الخدمة والعكس)
     */
    public function createMutualRating(
        ServiceRequest $serviceRequest,
        User $user1,
        User $user2,
        array $rating1, // ['rating' => int, 'comment' => string]
        array $rating2  // ['rating' => int, 'comment' => string]
    ) {
        // التحقق من صحة التقييمات
        if (!Rating::isValidRating($rating1['rating']) || !Rating::isValidRating($rating2['rating'])) {
            throw new \Exception('التقييم يجب أن يكون بين 1 و 5');
        }

        // التحقق من أن الطلب مكتمل
        if ($serviceRequest->status !== ServiceRequest::STATUS_COMPLETED) {
            throw new \Exception('يمكن التقييم فقط للطلبات المكتملة');
        }

        // التحقق من عدم التقييم المتكرر
        if (Rating::where('service_request_id', $serviceRequest->id)
            ->whereIn('rater_id', [$user1->id, $user2->id])
            ->exists()) {
            throw new \Exception('تم التقييم مسبقاً لهذا الطلب');
        }

        DB::beginTransaction();
        try {
            // تقييم من user1 لـ user2
            $ratingModel1 = Rating::create([
                'service_request_id' => $serviceRequest->id,
                'rater_id' => $user1->id,
                'rated_id' => $user2->id,
                'rating' => $rating1['rating'],
                'comment' => $rating1['comment'] ?? null,
            ]);

            // تقييم من user2 لـ user1
            $ratingModel2 = Rating::create([
                'service_request_id' => $serviceRequest->id,
                'rater_id' => $user2->id,
                'rated_id' => $user1->id,
                'rating' => $rating2['rating'],
                'comment' => $rating2['comment'] ?? null,
            ]);

            DB::commit();
            return [$ratingModel1, $ratingModel2];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * إنشاء تقييم واحد (للتوافق مع الكود القديم)
     */
    public function createSingleRating(
        ServiceRequest $serviceRequest,
        User $rater,
        User $rated,
        int $rating,
        string $comment = null
    ) {
        // التحقق من صحة التقييم
        if (!Rating::isValidRating($rating)) {
            throw new \Exception('التقييم يجب أن يكون بين 1 و 5');
        }

        // التحقق من أن الطلب مكتمل
        if ($serviceRequest->status !== ServiceRequest::STATUS_COMPLETED) {
            throw new \Exception('يمكن التقييم فقط للطلبات المكتملة');
        }

        // التحقق من عدم التقييم المتكرر
        if (Rating::where('service_request_id', $serviceRequest->id)
            ->where('rater_id', $rater->id)
            ->where('rated_id', $rated->id)
            ->exists()) {
            throw new \Exception('لقد قمت بتقييم هذا المستخدم مسبقاً');
        }

        DB::beginTransaction();
        try {
            $ratingModel = Rating::create([
                'service_request_id' => $serviceRequest->id,
                'rater_id' => $rater->id,
                'rated_id' => $rated->id,
                'rating' => $rating,
                'comment' => $comment,
            ]);

            DB::commit();
            return $ratingModel;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Rating $rating, array $data)
    {
        if (isset($data['rating']) && !Rating::isValidRating($data['rating'])) {
            throw new \Exception('التقييم يجب أن يكون بين 1 و 5');
        }

        $rating->update($data);
        return $rating;
    }

    public function delete($id)
    {
        $rating = Rating::findOrFail($id);
        return $rating->delete();
    }

    /**
     * الحصول على متوسط التقييمات لمستخدم
     */
    public function getAverageRatingForUser(User $user): float
    {
        return Rating::averageRatingForUser($user->id);
    }
}
