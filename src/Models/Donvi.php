<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;
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
        $this->table = config('ovic.table.donvi', 'donvi');

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

    public function scopegetDonvi( $query )
    {
        $user = Auth::user();

        if ( $user->status == 3 ) {
            $query = $query->where('id', '>', '0')
                ->get([
                    'id',
                    'tendonvi',
                    'parent_id'
                ]);
        } else {
            $query = $query->where('id', $user->donvi_id);

            if ( !empty($user->donvi_ids) && $user->donvi_ids !== 0 ) {
                $query = $query->orWhere(
                    function ( $query ) use ( $user ) {
                        $query->whereIn('id', maybe_unserialize($user->donvi_ids));
                    }
                );
            }
            $query = $query->get([
                'id',
                'tendonvi',
                'parent_id'
            ]);
        }

        $donvi = $query->collect()->groupBy('parent_id');

        return $donvi;
    }
}
