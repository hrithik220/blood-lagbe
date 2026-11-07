<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'patient_name',
        'blood_group',
        'hospital_name',
        'hospital_address',
        'district',
        'area',
        'required_date',
        'bags_needed',
        'contact_number',
        'emergency_level',
        'status',
        'notes',
        'rejection_reason',
        'approved_at',
        'fulfilled_at',
    ];

    protected $casts = [
        'required_date' => 'date',
        'approved_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donationRecords()
    {
        return $this->hasMany(DonationRecord::class);
    }

    public function donorInvitations()
    {
        return $this->hasMany(DonorInvitation::class);
    }

    public function isUrgent(): bool
    {
        return in_array($this->emergency_level, ['urgent', 'critical']);
    }

    public function isCritical(): bool
    {
        return $this->emergency_level === 'critical';
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'in_progress' => 'purple',
            'fulfilled' => 'green',
            'cancelled' => 'gray',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getEmergencyBadgeColorAttribute(): string
    {
        return match($this->emergency_level) {
            'normal' => 'green',
            'urgent' => 'yellow',
            'critical' => 'red',
            default => 'gray',
        };
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('emergency_level', ['urgent', 'critical']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
