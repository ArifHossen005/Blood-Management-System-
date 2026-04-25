<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'patient_name',
        'blood_group',
        'units_needed',
        'hospital_name',
        'hospital_address',
        'contact_number',
        'needed_date',
        'urgency',       // normal, urgent, emergency
        'reason',
        'status',        // pending, approved, fulfilled, cancelled
        'fulfilled_by',
        'notes',
    ];

    protected $casts = [
        'needed_date' => 'date',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function fulfilledByDonor()
    {
        return $this->belongsTo(User::class, 'fulfilled_by');
    }
}
