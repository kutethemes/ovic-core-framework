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

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }

    public function scopegetDonvi( $query )
    {
        $user = Auth::user();

        if ( $user->status == 3 ) {
            return $query->where(
                [
                    [ 'status', 1 ],
                    [ 'parent_id', 0 ]
                ])
                ->with([
                    'children' => function ( $query ) {
                        $query->where(
                            [
                                [ 'status', 1 ],
                            ]
                        );
                    }
                ]);
        } else {
            $donvi_ids = maybe_unserialize($user->donvi_ids);
            $query     = $query->where(
                [
                    [ 'status', '1' ],
                    [ 'id', $user->donvi_id ]
                ]
            );
            if ( !empty($donvi_ids) && $donvi_ids !== 0 ) {
                return $query->orWhere(
                    function ( $query ) use ( $donvi_ids ) {
                        $query->whereIn('id', $donvi_ids);
                    }
                );
            } else {
                return $query->orWhere('parent_id', $user->donvi_id)
                    ->with([
                        'children' => function ( $query ) {
                            $query->where(
                                [
                                    [ 'status', 1 ],
                                ]
                            );
                        }
                    ]);
            }
        }
    }
}
