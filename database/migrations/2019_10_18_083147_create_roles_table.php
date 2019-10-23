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
				$table->string( 'title', 150 );
				$table->text( 'description' );
				$table->text( 'usecase_ids' )->nullable();
				$table->integer( 'ordering' )->unsigned()->default( 100 );
				$table->tinyInteger( 'status' )->unsigned()->nullable();
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
