<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NewDonorRegistered extends Notification
{
    public function __construct(public User $donor) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_donor_registered',
            'action_url'  => '/admin/donors?status=temporary',
            'icon'        => 'bi-person-plus-fill',
            'color'       => 'warning',
            'donor_id'    => $this->donor->id,
            'donor_name'  => $this->donor->name,
            'blood_group' => $this->donor->blood_group,
        ];
    }
}
