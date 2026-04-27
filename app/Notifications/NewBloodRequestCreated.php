<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Notifications\Notification;

class NewBloodRequestCreated extends Notification
{
    public function __construct(public BloodRequest $bloodRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $urgencyColor = match ($this->bloodRequest->urgency) {
            'emergency' => 'danger',
            'urgent'    => 'warning',
            default     => 'info',
        };

        return [
            'type'         => 'new_blood_request',
            'action_url'   => '/admin/blood-requests',
            'icon'         => 'bi-droplet-fill',
            'color'        => $urgencyColor,
            'blood_group'  => $this->bloodRequest->blood_group,
            'urgency'      => $this->bloodRequest->urgency,
            'patient_name' => $this->bloodRequest->patient_name,
            'requester'    => $this->bloodRequest->requester?->name,
        ];
    }
}
