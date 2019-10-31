<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;

class Ucases extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ucases';

    public static function hasTable()
    {
        if ( Schema::hasTable('ucases') ) {
            return true;
        }

        return false;
    }
}
