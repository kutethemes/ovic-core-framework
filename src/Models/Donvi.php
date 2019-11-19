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

    public function __construct( array $attributes = [] )
    {
        $this->table = config('ovic.table.donvi.name', 'donvi');

        parent::__construct($attributes);
    }

    public function scopehasTable( $query )
    {
        if ( Schema::hasTable($this->table) ) {
            return true;
        }

        return false;
    }

    public function scopeTableName( $query )
    {
        return $this->table;
    }
}
