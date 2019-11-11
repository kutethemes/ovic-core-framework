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

    public function setRoleIdsAttribute( $value )
    {
        $this->attributes['role_ids'] = maybe_serialize($value);
    }

    public function setDonviIdsAttribute( $value )
    {
        $this->attributes['donvi_ids'] = maybe_serialize($value);
    }

    public function getRoleIdsAttribute( $value )
    {
        return maybe_unserialize($value);
    }

    public function getDonviIdsAttribute( $value )
    {
        return maybe_unserialize($value);
    }
}
