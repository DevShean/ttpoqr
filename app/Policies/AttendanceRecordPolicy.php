<?php

namespace App\Policies;

use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRecordPolicy
{
    public function delete(User $user, AttendanceRecord $record): bool
    {
        // Always allow if user is admin
        if ($user->usertype_id === 1) {
            return true;
        }
        
        // Load the form relationship if not already loaded
        $form = $record->load('form')->form;
        
        // If form is null, deny access (except for admins, already checked above)
        if (!$form) {
            return false;
        }
        
        // Check if user owns this record's form
        return $user->id === $form->admin_id;
    }
}
