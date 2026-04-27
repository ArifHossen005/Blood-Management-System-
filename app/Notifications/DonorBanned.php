<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DonorBanned extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'donor_banned',
            'action_url' => '/donor/dashboard',
            'icon'       => 'bi-slash-circle-fill',
            'color'      => 'danger',
        ];
    }
}
