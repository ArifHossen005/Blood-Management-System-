<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DonorApproved extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'donor_approved',
            'action_url' => '/donor/profile',
            'icon'       => 'bi-check-circle-fill',
            'color'      => 'success',
        ];
    }
}
