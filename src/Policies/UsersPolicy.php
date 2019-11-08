<?php

namespace Ovic\Framework;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    public function edit_user( User $user )
    {
        return true;
    }
}
