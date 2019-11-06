<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;

class Donvi extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'donvi';

    public static function hasTable()
    {
        if ( Schema::hasTable('donvi') ) {
            return true;
        }

        return false;
    }
}
