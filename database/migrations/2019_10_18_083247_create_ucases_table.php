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
                $table->string( 'name' );
				$table->integer( 'parent_id' )->unsigned();
				$table->text( 'router' );
				$table->tinyInteger( 'ordering' )->unsigned()->default(99);
				$table->string( 'position', 15 )->default('left');
				$table->tinyInteger( 'access' )->unsigned()->default(0);
				$table->tinyInteger( 'status' )->unsigned()->default(1);
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
