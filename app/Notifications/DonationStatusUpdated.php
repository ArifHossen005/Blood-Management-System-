<?php

namespace App\Notifications;

use App\Models\DonationHistory;
use Illuminate\Notifications\Notification;

class DonationStatusUpdated extends Notification
{
    public function __construct(public DonationHistory $donation, public string $newStatus) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'donation_' . $this->newStatus,
            'action_url' => '/donor/donations',
            'icon'       => $this->newStatus === 'verified' ? 'bi-patch-check-fill' : 'bi-patch-minus-fill',
            'color'      => $this->newStatus === 'verified' ? 'success' : 'danger',
            'status'     => $this->newStatus,
            'blood_group'=> $this->donation->blood_group,
        ];
    }
}
