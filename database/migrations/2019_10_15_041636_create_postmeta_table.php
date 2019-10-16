<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostmetaTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'postmeta',
			function ( Blueprint $table ) {
				$table->bigIncrements( 'meta_id' );
				$table->bigInteger( 'post_id' );
				$table->text( 'meta_key' );
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
		Schema::dropIfExists( 'postmeta' );
	}
}
