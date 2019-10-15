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

	public static function post_meta( $post_id, $meta_key )
	{
		$post_id = abs( intval( $post_id ) );
		if ( !$post_id ) {
			return false;
		}
		if ( !$meta_key ) {
			return false;
		}

		return Postmeta::where( 'post_id', $post_id )
			->where( 'meta_key', $meta_key )
			->value( 'meta_value' );
	}
}