<?php

namespace App\Notifications;

use App\Models\DonationClaim;
use Illuminate\Notifications\Notification;

class DonationClaimRejected extends Notification
{
    public function __construct(public DonationClaim $claim) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'             => 'claim_rejected',
            'action_url'       => '/donor/claims',
            'icon'             => 'bi-x-circle-fill',
            'color'            => 'danger',
            'rejection_reason' => $this->claim->rejection_reason,
        ];
    }
}
