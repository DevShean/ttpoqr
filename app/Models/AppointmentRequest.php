<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'availability_id',
        'purpose',
        'appointment_time',
        'status',
        'rejection_reason',
        'rejected_at',
        'is_archived',
    ];

    protected $casts = [
        'status' => 'string',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }
}
