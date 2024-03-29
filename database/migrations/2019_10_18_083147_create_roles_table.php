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
        Schema::create('roles',
            function ( Blueprint $table ) {
                $table->bigIncrements('id');
                $table->string('name', 100)->unique();
                $table->string('title', 100);
                $table->text('description')->nullable();
                $table->text('ucase_ids')->nullable();
                $table->integer('ordering')->default(99);
                $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('roles');
    }
}
