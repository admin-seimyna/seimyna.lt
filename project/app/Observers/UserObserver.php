<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    /**
     * @param User $user
     */
    public function creating(User $user)
    {
        $user->password = Hash::make($user->password);
    }

    /**
     * @param User $user
     */
    public function updating(User $user)
    {
        $dirty = $user->getDirty();
        if (isset($dirty['password'])) {
            $user->password = Hash::make($user->password);
        }
    }
}
