<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'posts',
			function ( Blueprint $table ) {
				$table->bigIncrements( 'id' );
				$table->text( 'title' );
				$table->text( 'name' );
				$table->string( 'post_type', 20 );
				$table->string( 'status', 20 ); // publish/draft/opened/closed/private
				$table->text( 'content' );
				$table->text( 'user_id' );
				$table->text( 'owner_id' );
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
		Schema::dropIfExists( 'posts' );
	}
}
