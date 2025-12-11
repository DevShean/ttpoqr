<?php

namespace App\Policies;

use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRecordPolicy
{
    public function delete(User $user, AttendanceRecord $record): bool
    {
        return $user->id === $record->form->admin_id || $user->usertype_id === 1;
    }
}
