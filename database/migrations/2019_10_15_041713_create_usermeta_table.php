<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsermetaTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'usermeta',
			function ( Blueprint $table ) {
				$table->bigIncrements( 'umeta_id' );
				$table->bigInteger( 'user_id' );
				$table->string( 'meta_key' );
				$table->text( 'meta_value' );
				$table->timestamps();
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'usermeta' );
	}
}
