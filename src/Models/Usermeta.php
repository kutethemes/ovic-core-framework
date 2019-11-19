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

    public function __construct( array $attributes = [] )
    {
        $this->table = config('ovic.table.usermeta.name', 'usermeta');

        parent::__construct($attributes);
    }

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'umeta_id';

	public static function get_meta( $post_id )
	{
		$meta_data = [];
		$postmeta  = Usermeta::where( 'user_id', $post_id )->get()->toArray();
		if ( !empty( $postmeta ) ) {
			foreach ( $postmeta as $meta ) {
				$meta_data[$meta['meta_key']] = maybe_unserialize( $meta['meta_value'] );
			}
		}

		return $meta_data;
	}

	public static function get_user_meta( $user_id, $meta_key )
	{
		$user_id = abs( intval( $user_id ) );
		if ( !$user_id ) {
			return false;
		}
		if ( !$meta_key ) {
			return false;
		}

		$meta_value = Usermeta::where( 'user_id', $user_id )
			->where( 'meta_key', $meta_key )
			->value( 'meta_value' );

		return maybe_unserialize( $meta_value );
	}

	public static function update_user_meta( $user_id, $meta_key, $meta_value )
	{
		$user_id = abs( intval( $user_id ) );
		if ( !$user_id ) {
			return false;
		}
		if ( !$meta_key ) {
			return false;
		}

		Postmeta::where( 'meta_key', $meta_key )
			->where( 'user_id', $user_id )
			->update(
				[
					'meta_value' => maybe_serialize( $meta_value ),
				]
			);

		return true;
	}
}
