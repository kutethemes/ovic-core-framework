<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable2 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( Schema::hasTable( 'users' ) && !Schema::hasColumn( 'users', 'role_ids' ) ) {
			Schema::table( 'users',
				function ( Blueprint $table ) {
					$table->text( 'role_ids' )->after( 'name' );
					$table->text( 'donvi_ids' )->after( 'role_ids' );
					$table->text( 'donvi_id' )->after( 'donvi_ids' );
					$table->tinyInteger( 'status' )->after( 'donvi_id' )->unsigned();
				}
			);
		} else {
			Schema::create( 'users',
				function ( Blueprint $table ) {
					$table->bigIncrements( 'id' );
					$table->string( 'name' );
					$table->text( 'role_ids' );
					$table->text( 'donvi_ids' );
					$table->text( 'donvi_id' );
					$table->tinyInteger( 'status' )->unsigned();
					$table->string( 'email' )->unique();
					$table->timestamp( 'email_verified_at' )->nullable();
					$table->string( 'password' );
					$table->rememberToken();
					$table->timestamps();
				}
			);
		}
	}
}
