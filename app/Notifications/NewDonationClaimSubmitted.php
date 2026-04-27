<?php

namespace App\Notifications;

use App\Models\DonationClaim;
use Illuminate\Notifications\Notification;

class NewDonationClaimSubmitted extends Notification
{
    public function __construct(public DonationClaim $claim) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_claim_submitted',
            'action_url'  => '/admin/claims?status=pending',
            'icon'        => 'bi-award',
            'color'       => 'warning',
            'donor_name'  => $this->claim->user?->name,
            'blood_group' => $this->claim->user?->blood_group,
        ];
    }
}
