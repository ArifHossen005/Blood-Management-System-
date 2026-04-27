<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DonationClaim extends Model
{
    protected $fillable = [
        'user_id', 'blood_request_id', 'donation_date', 'hospital_name',
        'location', 'notes', 'status', 'certificate_number',
        'approved_by', 'approved_at', 'rejection_reason',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'approved_at'   => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Helpers ───────────────────────────────────────────────
    public static function generateCertificateNumber(int $userId): string
    {
        do {
            $number = 'BMS-' . date('Y') . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(Str::random(6));
        } while (static::where('certificate_number', $number)->exists());

        return $number;
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }
}
