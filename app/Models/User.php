<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'blood_group',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'district',
        'division',
        'profile_image',
        'nid_number',
        'emergency_contact',
        'last_donation_date',
        'total_donations',
        'role',               // admin, sub_admin, donor
        'permissions',        // json array (sub_admin only)
        'status',             // temporary, approved, rejected, banned
        'is_available',       // willing to donate now
        'health_notes',
        'weight',
        'approved_at',
        'approved_by',
        'contact_visible',    // admin toggle
        'address_visible',    // admin toggle
        'locale',             // ui language (bn, en)
        'theme',              // ui theme (light, dark)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'password'           => 'hashed',
        'date_of_birth'      => 'date',
        'last_donation_date' => 'date',
        'approved_at'        => 'datetime',
        'is_available'       => 'boolean',
        'contact_visible'    => 'boolean',
        'address_visible'    => 'boolean',
        'permissions'        => 'array',
    ];

    // ─── Permission catalog ────────────────────────────────
    public static function availablePermissions(): array
    {
        return [
            'approve_donors'        => __('ui.permissions.approve_donors'),
            'edit_donors'           => __('ui.permissions.edit_donors'),
            'manage_blood_requests' => __('ui.permissions.manage_blood_requests'),
            'view_donations'        => __('ui.permissions.view_donations'),
            'manage_donations'      => __('ui.permissions.manage_donations'),
        ];
    }

    // ─── Scopes ────────────────────────────────────────────
    public function scopeAdmins($q)    { return $q->where('role', 'admin'); }
    public function scopeSubAdmins($q) { return $q->where('role', 'sub_admin'); }
    public function scopeDonors($q)    { return $q->where('role', 'donor'); }
    public function scopeApproved($q)  { return $q->where('status', 'approved'); }
    public function scopeTemporary($q) { return $q->where('status', 'temporary'); }
    public function scopeAvailable($q) { return $q->where('is_available', true); }

    public function scopeBloodGroup($q, $group)
    {
        return $q->where('blood_group', $group);
    }

    // ─── Accessors ─────────────────────────────────────────
    public function getIsFullMemberAttribute(): bool
    {
        return $this->status === 'approved';
    }

    public function getIsTemporaryAttribute(): bool
    {
        return $this->status === 'temporary';
    }

    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image && file_exists(public_path('uploads/profiles/' . $this->profile_image))) {
            $version = $this->updated_at?->timestamp ?? time();
            return asset('uploads/profiles/' . $this->profile_image) . '?v=' . $version;
        }
        return asset('images/default-avatar.png');
    }

    public function getCanDonateAttribute(): bool
    {
        return $this->isEligibleToDonate();
    }

    // ─── Eligibility ───────────────────────────────────────
    public function isEligibleToDonate(): bool
    {
        if (!$this->last_donation_date) {
            return true;
        }
        $eligible = $this->eligibleDate();
        return $eligible !== null && $eligible->lessThanOrEqualTo(now()->startOfDay());
    }

    public function eligibleDate(): ?\Carbon\Carbon
    {
        if (!$this->last_donation_date || !$this->gender) {
            return null;
        }
        $days = $this->gender === 'female' ? 120 : 90;
        return $this->last_donation_date->copy()->addDays($days);
    }

    // ─── Role / Permission helpers ─────────────────────────
    public function isMainAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSubAdmin(): bool
    {
        return $this->role === 'sub_admin';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isMainAdmin()) {
            return true;
        }
        if (!$this->isSubAdmin()) {
            return false;
        }
        return in_array($permission, (array) $this->permissions, true);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $p) {
            if ($this->hasPermission($p)) {
                return true;
            }
        }
        return false;
    }

    public function routePrefix(): string
    {
        return match ($this->role) {
            'admin'     => 'admin',
            'sub_admin' => 'sub-admin',
            default     => 'donor',
        };
    }

    // ─── Relationships ─────────────────────────────────────
    public function donationHistory()
    {
        return $this->hasMany(DonationHistory::class, 'donor_id');
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class, 'requester_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
