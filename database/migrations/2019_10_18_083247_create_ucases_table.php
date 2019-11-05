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
        Schema::create('ucases',
            function ( Blueprint $table ) {
                $table->bigIncrements('id');
                $table->string('slug', 150)->unique();
                $table->string('title');
                $table->integer('parent_id')->default(0);
                $table->text('router'); // Array: description, icon, module, controller, custom_link
                $table->tinyInteger('ordering')->default(99);
                $table->string('position', 15)->default('left');
                $table->tinyInteger('access')->default(1);
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
        Schema::dropIfExists('ucases');
    }
}
