<?php

namespace Ovic\Framework;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny( User $user )
    {
        return true;
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $role
     * @return mixed
     */
    public function view( User $user, Roles $roles )
    {
        return true;
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create( User $user )
    {
        return $user->id > 0;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $role
     * @return mixed
     */
    public function update( User $user, Roles $role )
    {
        return $user->id == $role->user_id;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $role
     * @return mixed
     */
    public function delete( User $user, Roles $role )
    {
        return $user->id == $role->user_id;
    }

    /**
     * Determine whether the user can restore the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $role
     * @return mixed
     */
    public function restore( User $user, Roles $role )
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $role
     * @return mixed
     */
    public function forceDelete( User $user, Roles $role )
    {
        //
    }
}
