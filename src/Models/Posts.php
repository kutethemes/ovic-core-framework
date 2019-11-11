<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
                return [ $item['meta_key'] => maybe_unserialize($item['meta_value']) ];
            }
        )->toArray();
    }
}
