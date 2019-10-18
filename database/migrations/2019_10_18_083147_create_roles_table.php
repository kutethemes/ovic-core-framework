<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'roles',
			function ( Blueprint $table ) {
				$table->bigIncrements( 'id' );
				$table->string( 'name', 150 );
				$table->text( 'description' );
				$table->text( 'usecase_ids' );
				$table->integer( 'ordering', 11 );
				$table->tinyInteger( 'status', 4 );
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
		Schema::dropIfExists( 'roles' );
	}
}
