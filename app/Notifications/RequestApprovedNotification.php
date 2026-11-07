<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public BloodRequest $bloodRequest) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Blood Request Has Been Approved — Blood Lagbe')
            ->greeting('Great news, ' . $notifiable->name . '!')
            ->line('Your blood request for ' . $this->bloodRequest->patient_name . ' (' . $this->bloodRequest->blood_group . ') has been approved.')
            ->line('Hospital: ' . $this->bloodRequest->hospital_name)
            ->line('Required Date: ' . $this->bloodRequest->required_date->format('d M, Y'))
            ->action('View Request', route('dashboard.requests.show', $this->bloodRequest))
            ->line('Donors in your area will be notified. Thank you for using Blood Lagbe.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'request_approved',
            'title' => 'Blood Request Approved',
            'message' => 'Your blood request for ' . $this->bloodRequest->patient_name . ' (' . $this->bloodRequest->blood_group . ') has been approved.',
            'request_id' => $this->bloodRequest->id,
            'url' => route('dashboard.requests.show', $this->bloodRequest),
        ];
    }
}
