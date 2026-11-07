<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DonorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_group',
        'district',
        'area',
        'phone',
        'last_donation_date',
        'is_available',
        'is_public',
        'bio',
        'total_donations',
    ];

    protected $casts = [
        'last_donation_date' => 'date',
        'is_available' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the donor may be eligible to donate based on the 90-day guideline.
     * This is a general reminder only. Eligibility must be confirmed by a medical professional.
     */
    public function mayBeEligible(): bool
    {
        if (!$this->last_donation_date) {
            return true;
        }
        return $this->last_donation_date->diffInDays(now()) >= 90;
    }

    public function getDaysSinceLastDonationAttribute(): ?int
    {
        if (!$this->last_donation_date) {
            return null;
        }
        return $this->last_donation_date->diffInDays(now());
    }
}
