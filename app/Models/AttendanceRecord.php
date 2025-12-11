<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_form_id',
        'qr_id',
        'name',
        'gender',
        'address',
        'signature',
        'family_support',
        'contact_number',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(AttendanceForm::class, 'attendance_form_id');
    }

    public function qrToken(): BelongsTo
    {
        return $this->belongsTo(QrToken::class, 'qr_id');
    }
}
