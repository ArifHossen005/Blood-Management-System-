<?php

namespace App\Notifications;

use App\Models\DonationHistory;
use Illuminate\Notifications\Notification;

class NewDonationSubmitted extends Notification
{
    public function __construct(public DonationHistory $donation) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_donation_submitted',
            'action_url'  => '/admin/donation-histories?status=pending',
            'icon'        => 'bi-clipboard2-pulse-fill',
            'color'       => 'info',
            'donor_name'  => $this->donation->donor?->name,
            'blood_group' => $this->donation->blood_group,
        ];
    }
}
