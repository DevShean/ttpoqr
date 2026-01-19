<?php

namespace App\Policies;

use App\Models\AttendanceForm;
use App\Models\User;

class AttendanceFormPolicy
{
    public function view(User $user, AttendanceForm $form): bool
    {
        return $user->id === ($form->admin_id ?? null) || $user->usertype_id === 1;
    }

    public function update(User $user, AttendanceForm $form): bool
    {
        return $user->id === ($form->admin_id ?? null) || $user->usertype_id === 1;
    }

    public function delete(User $user, AttendanceForm $form): bool
    {
        return $user->id === ($form->admin_id ?? null) || $user->usertype_id === 1;
    }
}
