<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Posts extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table   = 'posts';
    protected $appends = [ 'meta' ];

    /*
     * Default all Where Condition
     * */
    public function newQuery( $excludeDeleted = true )
    {
        $user = Auth::user();
        $args = [
            [ 'user_id', '>=', 0 ]
        ];
        return parent::newQuery($excludeDeleted)
            ->where($args);
    }

    /*
     * Owner Where Condition
     * */
    public function scopeOwner( $query, $owner_id = null )
    {
        $user = Auth::user();
        if ( $owner_id != null ) {
            $user_id = $owner_id;
        } else {
            $user_id = $user->id;
        }
        $args = [
            [ 'owner_id', '>=', 0 ]
        ];
        if ( $user->status != 3 ) {
            $args = [
                [ 'owner_id', $user_id ]
            ];
        }

        return $query->where($args);
    }

    /**
     * Get the user that owns the phone.
     */
    public function meta()
    {
        return $this->hasMany(Postmeta::class, 'post_id');
    }

    public function getMetaAttribute()
    {
        return $this->meta()->get()->collect()->mapWithKeys(
            function ( $item, $key ) {
                return [ $item['meta_key'] => $item['meta_value'] ];
            }
        )->toArray();
    }

    /**
     * Destroy the models for the given IDs.
     *
     * @param  Collection|array|int  $ids
     * @return array
     */
    public static function destroy( $ids )
    {
        // We'll initialize a count here so we will return the total number of deletes
        // for the operation. The developers can then check this number as a boolean
        // type value or get this total count of records deleted for logging, etc.
        $count   = 0;
        $deleted = [];

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
            Postmeta::where('post_id', $model->$key)->delete();
            if ( $model->delete() ) {
                $deleted[] = $model->$key;
                if ( $model->post_type = 'attachment' ) {
                    Storage::delete("/uploads/{$model->name}");
                }
                $count++;
            }
        }

        return [
            'ids'   => $deleted,
            'count' => $count
        ];
    }
}
