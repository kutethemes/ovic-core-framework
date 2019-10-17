<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;

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
	public function post()
	{
		return $this->belongsTo( Post::class );
	}

	public static function get_meta( $post_id )
	{
		$meta_data = [];
		$postmeta  = Postmeta::where( 'post_id', $post_id )->get()->toJson();
		$postmeta  = json_decode( $postmeta, true );
		if ( !empty( $postmeta ) ) {
			foreach ( $postmeta as $meta ) {
				$meta_data[$meta['meta_key']] = maybe_unserialize( $meta['meta_value'] );
			}
		}

		return $meta_data;
	}

	public static function get_post_meta( $post_id, $meta_key )
	{
		$post_id = abs( intval( $post_id ) );
		if ( !$post_id ) {
			return false;
		}
		if ( !$meta_key ) {
			return false;
		}

		$meta_value = Postmeta::where( 'post_id', $post_id )
			->where( 'meta_key', $meta_key )
			->value( 'meta_value' );

		return maybe_unserialize( $meta_value );
	}

	public static function update_post_meta( $post_id, $meta_key, $meta_value )
	{
		$post_id = abs( intval( $post_id ) );
		if ( !$post_id ) {
			return false;
		}
		if ( !$meta_key ) {
			return false;
		}

		Postmeta::where( 'meta_key', $meta_key )
			->where( 'post_id', $post_id )
			->update(
				[
					'meta_value' => maybe_serialize( $meta_value ),
				]
			);

		return true;
	}
}