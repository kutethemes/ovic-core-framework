<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;

class Roles extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    public static function hasTable()
    {
        if ( Schema::hasTable('roles') ) {
            return true;
        }

        return false;
    }
}
