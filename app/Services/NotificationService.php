<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\ProfileVisitNotification;
use App\Notifications\NewMessageNotification;
use App\Notifications\PaymentNotification;
use App\Notifications\FavoriteNotification;

class NotificationService
{
    public static function createNotification($userId, $type, $title, $message, $link = null, $sendEmail = false)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);

        if ($sendEmail && $userId) {
            $user = User::find($userId);
            if ($user && $user->email) {
                try {
                    $user->notify(new \App\Notifications\GenericNotification($title, $message, $link));
                } catch (\Exception $e) {
                    \Log::error('Failed to send email notification: ' . $e->getMessage());
                }
            }
        }

        return $notification;
    }

    public static function createMessageNotification($userId, $fromUserName, $chatUuid, $messagePreview = null)
    {
        $chatUrl = route('chat.index') . "?chat={$chatUuid}";
        
        $notification = self::createNotification(
            $userId,
            'message',
            'رسالة جديدة',
            "لديك رسالة جديدة من {$fromUserName}",
            $chatUrl
        );

        $user = User::find($userId);
        if ($user && $user->email) {
            try {
                $user->notify(new NewMessageNotification($fromUserName, $messagePreview ?? 'رسالة جديدة', $chatUrl));
            } catch (\Exception $e) {
                \Log::error('Failed to send message email notification: ' . $e->getMessage());
            }
        }

        return $notification;
    }

    public static function createVisitNotification($userId, $visitorName, $visitorUuid = null)
    {
        $visitorProfileUrl = $visitorUuid 
            ? route('website.profile', $visitorUuid)
            : route('website.my_account') . '#p7';

        $notification = self::createNotification(
            $userId,
            'visit',
            'زيارة جديدة',
            "زار ملفك الشخصي: {$visitorName}",
            $visitorProfileUrl
        );

        $user = User::find($userId);
        if ($user && $user->email) {
            try {
                $user->notify(new ProfileVisitNotification($visitorName, $visitorProfileUrl));
            } catch (\Exception $e) {
                \Log::error('Failed to send visit email notification: ' . $e->getMessage());
            }
        }

        return $notification;
    }

    public static function createPaymentNotification($userId, $serviceName, $price = null, $status = 'pending')
    {
        $notification = self::createNotification(
            $userId,
            'payment',
            'دفع جديد',
            "تم شراء الخدمة: {$serviceName}",
            route('website.my_account') . '#p3'
        );

        $user = User::find($userId);
        if ($user && $user->email) {
            try {
                // Constructor: ($amount, $status, $transactionId = null)
                $user->notify(new PaymentNotification($price, $status));
            } catch (\Exception $e) {
                \Log::error('Failed to send payment email notification: ' . $e->getMessage());
            }
        }

        return $notification;
    }

    public static function createFavoriteNotification($userId, $favoriterName, $favoriterUuid)
    {
        $profileUrl = route('website.profile', $favoriterUuid);

        $notification = self::createNotification(
            $userId,
            'favorite',
            'إضافة للمفضلة',
            "أضافك {$favoriterName} إلى المفضلة",
            $profileUrl
        );

        $user = User::find($userId);
        if ($user && $user->email) {
            try {
                $user->notify(new FavoriteNotification($favoriterName, $profileUrl));
            } catch (\Exception $e) {
                \Log::error('Failed to send favorite email notification: ' . $e->getMessage());
            }
        }

        return $notification;
    }

    public static function createSubscriptionNotification($userId, $email)
    {
        return self::createNotification(
            $userId,
            'subscribe',
            'اشتراك جديد',
            "اشتراك جديد: {$email}",
            route('admin.subscribers.index')
        );
    }

    public static function createContactNotification($userId, $name)
    {
        return self::createNotification(
            $userId,
            'contact',
            'اتصال جديد',
            "رسالة جديدة من: {$name}",
            route('admin.contacts.index')
        );
    }

    public static function createAdminNotification($type, $title, $message, $link = null)
    {
        $admins = User::where('is_admin', 1)->get();
        
        foreach ($admins as $admin) {
            self::createNotification(
                $admin->id,
                $type,
                $title,
                $message,
                $link,
                true
            );
        }
    }
}

