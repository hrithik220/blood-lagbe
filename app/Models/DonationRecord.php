<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'blood_request_id',
        'donated_at',
        'bags_donated',
        'hospital_name',
        'district',
        'notes',
    ];

    protected $casts = [
        'donated_at' => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }
}
