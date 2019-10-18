<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUcasesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'ucases',
			function ( Blueprint $table ) {
				$table->bigIncrements( 'id' );
				$table->string( 'name', 100 );
				$table->string( 'router', 100 );
				$table->string( 'controller', 255 );
				$table->text( 'custom_link' );
				$table->integer( 'parent_id' )->unsigned();
				$table->string( 'icon', 100 );
				$table->text( 'description' );
				$table->tinyInteger( 'ordering' )->unsigned();
				$table->string( 'position', 15 );
				$table->tinyInteger( 'status' )->unsigned();
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
		Schema::dropIfExists( 'ucases' );
	}
}
