<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DonorRejected extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'donor_rejected',
            'action_url' => '/donor/dashboard',
            'icon'       => 'bi-x-circle-fill',
            'color'      => 'danger',
        ];
    }
}
