<?php

namespace Ovic\Framework;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;

class Users extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    public static function hasTable()
    {
        if ( Schema::hasTable('users') ) {
            return true;
        }

        return false;
    }

    /**
     * Get the user that owns the phone.
     */
    public function hasRole( $role )
    {
        return User::where('role_ids', $role)->get();
    }
}
