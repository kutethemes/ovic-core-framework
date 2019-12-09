<?php

namespace Ovic\Framework;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class Users extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table   = 'users';
    protected $appends = [ 'meta' ];

    public function __construct( array $attributes = [] )
    {
        $this->table = config('ovic.table.users', 'users');

        parent::__construct($attributes);
    }

    public function meta()
    {
        return $this->hasMany(Usermeta::class, 'user_id');
    }

    public function donvi()
    {
        return $this->belongsTo(Donvi::class);
    }

    public function getMetaAttribute()
    {
        return $this->meta()->get()->collect()->mapWithKeys(
            function ( $item, $key ) {
                return [ $item['meta_key'] => $item['meta_value'] ];
            }
        )->toArray();
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

    /**
     * Destroy the models for the given IDs.
     *
     * @param  Collection|array|int  $ids
     * @return int
     */
    public static function destroy( $ids )
    {
        // We'll initialize a count here so we will return the total number of deletes
        // for the operation. The developers can then check this number as a boolean
        // type value or get this total count of records deleted for logging, etc.
        $count = 0;

        if ( !is_numeric($ids) && !is_array($ids) && is_string($ids) ) {
            $ids = explode(',', $ids);
        }

        if ( $ids instanceof BaseCollection ) {
            $ids = $ids->all();
        }

        $ids = is_array($ids) ? $ids : func_get_args();

        // We will actually pull the models from the database table and call delete on
        // each of them individually so that their events get fired properly with a
        // correct set of attributes in case the developers wants to check these.
        $key = ( $instance = new static )->getKeyName();

        foreach ( $instance->whereIn($key, $ids)->get() as $model ) {
            if ( $model->status != 3 && $model->delete() ) {
                $count++;
            }
        }

        return $count;
    }
}
