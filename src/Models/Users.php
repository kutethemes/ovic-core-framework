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

    public function scopehasTable( $query )
    {
        if ( Schema::hasTable($this->table) ) {
            return true;
        }

        return false;
    }
}
