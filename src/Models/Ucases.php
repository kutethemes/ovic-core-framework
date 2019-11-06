<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;

class Ucases extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ucases';

    public function scopehasTable( $query )
    {
        if ( Schema::hasTable($this->table) ) {
            return true;
        }

        return false;
    }

    public function scopeMenus( $query, $position, $is_active = false )
    {
        $args = [
            [ 'position', $position ]
        ];

        if ( $is_active == true ) {
            $args[] = [ 'status', '1' ];
        }

        return $query->where($args)
            ->get()
            ->collect()
            ->each(function ( $item, $key ) {
                $item->router = json_decode($item->router, true);
            })
            ->sortBy('ordering')
            ->groupBy('parent_id')
            ->toArray();
    }
}
