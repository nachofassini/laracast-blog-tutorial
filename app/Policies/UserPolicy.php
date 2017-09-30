<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User  $loggedUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $loggedUser, User $user)
    {
        return $loggedUser->id == $user->id;
    }
}
