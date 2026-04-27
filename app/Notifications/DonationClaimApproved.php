<?php

namespace App\Notifications;

use App\Models\DonationClaim;
use Illuminate\Notifications\Notification;

class DonationClaimApproved extends Notification
{
    public function __construct(public DonationClaim $claim) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'claim_approved',
            'action_url'         => '/donor/certificate/' . $this->claim->id,
            'icon'               => 'bi-award-fill',
            'color'              => 'success',
            'certificate_number' => $this->claim->certificate_number,
        ];
    }
}
