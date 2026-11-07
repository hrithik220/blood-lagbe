<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_name',
        'author_role',
        'content',
        'rating',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function getNameAttribute(): string
    {
        return $this->author_name;
    }

    public function getMessageAttribute(): string
    {
        return $this->content;
    }

    public function getLocationAttribute(): string
    {
        return $this->author_role ?? 'Bangladesh';
    }

    public function getBloodGroupAttribute(): ?string
    {
        return null;
    }
}
