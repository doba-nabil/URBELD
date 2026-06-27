<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    public $amount;
    public $status;
    public $transactionId;

    public function __construct($amount, $status, $transactionId = null)
    {
        $this->amount = $amount;
        $this->status = $status;
        $this->transactionId = $transactionId;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->status === 'success' ? 'تم تأكيد عملية الدفع' : 'فشل في عملية الدفع';
        
        return (new MailMessage)
            ->subject($subject)
            ->view('emails.payment', [
                'user' => $notifiable,
                'amount' => $this->amount,
                'status' => $this->status,
                'transactionId' => $this->transactionId,
                'logoUrl' => config('settings.logo') ? asset(config('settings.logo')) : null,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'payment',
            'title' => $this->status === 'success' ? 'نجاح الدفع' : 'فشل الدفع',
            'message' => $this->status === 'success' 
                ? "تم استلام دفعة بمبلغ {$this->amount} ريال بنجاح." 
                : "عذراً، فشلت عملية الدفع بمبلغ {$this->amount} ريال.",
            'link' => route('profile.subscription'),
            'data' => [
                'amount' => $this->amount,
                'status' => $this->status,
                'transaction_id' => $this->transactionId
            ]
        ];
    }
}
