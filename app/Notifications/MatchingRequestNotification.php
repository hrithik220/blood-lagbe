<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MatchingRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public BloodRequest $bloodRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'matching_request',
            'title' => 'Blood Needed in Your Area!',
            'message' => 'A ' . $this->bloodRequest->blood_group . ' blood request has been posted near ' . $this->bloodRequest->district . '. Your help could save a life.',
            'request_id' => $this->bloodRequest->id,
            'url' => route('requests.show', $this->bloodRequest),
        ];
    }
}
