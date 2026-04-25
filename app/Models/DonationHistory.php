<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model
{
    use HasFactory;

    protected $table = 'donation_histories';

    protected $fillable = [
        'donor_id',
        'blood_group',
        'donation_date',
        'units',
        'hospital_name',
        'location',
        'recipient_name',
        'notes',
        'verified_by',
        'status', // pending, verified, rejected
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
