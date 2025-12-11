<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'user_id',
        'fname',
        'mname',
        'lname',
        'contactnum',
        'address',
        'city',
        'state',
        'zip',
        'civil_status',
        'gender',
        'avatar_path',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
