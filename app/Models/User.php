<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'district',
        'area',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Relationships
    public function donorProfile()
    {
        return $this->hasOne(DonorProfile::class);
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function donationRecords()
    {
        return $this->hasMany(DonationRecord::class, 'donor_id');
    }

    public function donorInvitations()
    {
        return $this->hasMany(DonorInvitation::class, 'donor_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
}
