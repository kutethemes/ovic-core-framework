<?php

namespace Ovic\Framework;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;

class Users extends User
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table   = 'users';
	protected $appends = [ 'donvi' ];

    public static function hasTable()
	{
		if ( Schema::hasTable( 'users' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the user that owns the phone.
	 */
	public function donvi()
	{
		return $this->hasMany( Donvi::class, 'user_id' );
	}

	public function getDonviAttribute()
	{
		return $this->donvi()->get()->toArray();
	}
}
