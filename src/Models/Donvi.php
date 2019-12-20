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
    	if ( !Auth::check() ){
    		return [];
		}

        $user = Auth::user();

        if ( $user->status == 3 ) {
            if ( empty($args) ) {
                $args['parent_id'] = [ 'parent_id', 0 ];
            }
            $args[] = [ 'status', 1 ];
            $query  = $query->where(array_values($args))
                ->with([
                    'children' => function ( $query ) use ( $args ) {
                        unset($args['parent_id']);
                        $query->where(
                            array_values($args)
                        );
                    }
                ]);

        } else {
            $args[]    = [ 'status', 1 ];
            $donvi_ids = maybe_unserialize($user->donvi_ids);
            $query     = $query->where('id', $user->donvi_id);

            if ( !empty($donvi_ids) && $donvi_ids !== 0 ) {
                $query = $query->with(
                    [
                        'children' => function ( $query ) use ( $args, $donvi_ids ) {
                            $query->whereIn('id', $donvi_ids)
                                ->where(
                                    array_values($args)
                                );
                        }
                    ]);
            } else {
                $query = $query->with(
                    [
                        'children' => function ( $query ) use ( $args ) {
                            $query->where(
                                array_values($args)
                            );
                        }
                    ]);
            }
        }

        if ( $level === true ) {
            return remove_level(
                $query->get()->toArray()
            );
        }

        if ( $level === false ) {
            return $query->get()->toArray();
        }

        return $query;
    }
}
