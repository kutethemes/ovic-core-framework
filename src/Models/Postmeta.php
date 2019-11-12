<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;

class Postmeta extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'postmeta';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'meta_id';

    /**
     * Get the post that owns the comment.
     */
    public function posts()
    {
        return $this->belongsTo(Posts::class);
    }

    public function setMetaValueAttribute( $value )
    {
        $this->attributes['meta_value'] = maybe_serialize($value);
    }

    public function getMetaValueAttribute( $value )
    {
        return maybe_unserialize($value);
    }

    public function scopeget_meta( $query, $post_id )
    {
        $meta_data = [];
        $postmeta  = $query->where('post_id', $post_id)
            ->get([ 'meta_key', 'meta_value' ])
            ->toArray();

        if ( !empty($postmeta) ) {
            foreach ( $postmeta as $meta ) {
                $meta_data[$meta['meta_key']] = $meta['meta_value'];
            }
        }

        return $meta_data;
    }

    public function scopeget_post_meta( $query, $post_id, $meta_key )
    {
        $post_id = abs(intval($post_id));
        if ( !$post_id ) {
            return false;
        }
        if ( !$meta_key ) {
            return false;
        }

        return $query->where('post_id', $post_id)
            ->where('meta_key', $meta_key)
            ->value('meta_value');
    }

    public function scopeupdate_post_meta( $query, $post_id, $meta_key, $meta_value )
    {
        $post_id = abs(intval($post_id));
        if ( !$post_id ) {
            return false;
        }
        if ( !$meta_key ) {
            return false;
        }

        $query->where('meta_key', $meta_key)
            ->where('post_id', $post_id)
            ->update(
                [
                    'meta_value' => maybe_serialize($meta_value),
                ]
            );

        return true;
    }
}
