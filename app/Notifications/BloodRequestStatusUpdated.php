<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Notifications\Notification;

class BloodRequestStatusUpdated extends Notification
{
    public function __construct(public BloodRequest $bloodRequest, public string $newStatus) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $colorMap = [
            'approved'  => 'success',
            'fulfilled' => 'info',
            'cancelled' => 'secondary',
        ];

        return [
            'type'             => 'blood_request_updated',
            'action_url'       => '/donor/blood-requests',
            'icon'             => 'bi-droplet-fill',
            'color'            => $colorMap[$this->newStatus] ?? 'secondary',
            'status'           => $this->newStatus,
            'blood_group'      => $this->bloodRequest->blood_group,
            'patient_name'     => $this->bloodRequest->patient_name,
        ];
    }
}
