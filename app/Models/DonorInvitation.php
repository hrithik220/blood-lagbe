<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'status',
        'message',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}
