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
					$table->text( 'role_ids' )->after( 'name' )->nullable();
					$table->text( 'donvi_ids' )->after( 'role_ids' )->nullable();
					$table->text( 'donvi_id' )->after( 'donvi_ids' )->nullable();
					$table->tinyInteger( 'status' )->after( 'donvi_id' )->unsigned()->default( 1 );
				}
			);
		} else {
			Schema::create( 'users',
				function ( Blueprint $table ) {
					$table->bigIncrements( 'id' );
					$table->string( 'name' );
					$table->text( 'role_ids' )->nullable();
					$table->text( 'donvi_ids' )->nullable();
					$table->text( 'donvi_id' )->nullable();
					$table->tinyInteger( 'status' )->unsigned()->default( 1 );
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
