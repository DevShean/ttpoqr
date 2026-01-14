<?php

namespace App\Traits;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

trait LogsAdminActions
{
    /**
     * Log an admin action
     */
    public function logAdminAction(
        string $action,
        string $actionType,
        ?string $description = null,
        ?string $relatedModel = null,
        ?int $relatedId = null
    ) {
        if (!Auth::check()) {
            return;
        }

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => $action,
            'action_type' => $actionType,
            'description' => $description,
            'related_model' => $relatedModel,
            'related_id' => $relatedId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
