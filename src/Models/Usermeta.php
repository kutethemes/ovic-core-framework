<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Usermeta extends Eloquent
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'usermeta';
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'umeta_id';

	public static function user_meta( $user_id, $meta_key )
	{
		$user_id = abs( intval( $user_id ) );
		if ( !$user_id ) {
			return false;
		}
		if ( !$meta_key ) {
			return false;
		}

		return Usermeta::where( 'post_id', $user_id )
			->where( 'meta_key', $meta_key )
			->value( 'meta_value' );
	}
}