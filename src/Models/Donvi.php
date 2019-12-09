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
    protected $table   = 'donvi';
    protected $appends = [ 'users' ];

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

    public function users()
    {
        return $this->hasMany(Users::class);
    }

    public function getUsersAttribute()
    {
        return $this->users()->get()->toArray();
    }

    public function scopegetDonvi( $query, $level = false, $args = [] )
    {
        $user = Auth::user();

        if ( $user->status == 3 ) {
            $query = $query->where(
                [
                    [ 'status', 1 ],
                    [ 'parent_id', 0 ]
                ])
                ->with([
                    'children' => function ( $query ) use ( $args ) {
                        $args[] = [ 'status', 1 ];
                        $query->where($args);
                    }
                ])
                ->get()
                ->toArray();
        } else {
            $donvi_ids = maybe_unserialize($user->donvi_ids);
            $query     = $query->where(
                [
                    [ 'status', '1' ],
                    [ 'id', $user->donvi_id ]
                ]
            );
            if ( !empty($donvi_ids) && $donvi_ids !== 0 ) {
                $query = $query->with(
                    [
                        'children' => function ( $query ) use ( $donvi_ids ) {
                            $query->whereIn('id', $donvi_ids);
                        }
                    ])
                    ->get()
                    ->toArray();
            } else {
                $query = $query->with(
                    [
                        'children' => function ( $query ) use ( $args ) {
                            $args[] = [ 'status', 1 ];
                            $query->where($args);
                        }
                    ])
                    ->get()
                    ->toArray();
            }
        }

        if ( $level == true ) {
            return remove_level($query);
        }

        return $query;
    }
}
