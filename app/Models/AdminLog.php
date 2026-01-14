<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'admin_logs';

    protected $fillable = [
        'admin_id',
        'action',
        'action_type',
        'description',
        'related_model',
        'related_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Get human-readable action type
    public function getActionTypeLabel()
    {
        $labels = [
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'scheduled' => 'Scheduled',
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'archived' => 'Archived',
        ];

        return $labels[$this->action_type] ?? ucfirst($this->action_type);
    }
}
