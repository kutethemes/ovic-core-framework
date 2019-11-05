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
        Schema::create('posts',
            function ( Blueprint $table ) {
                $table->bigIncrements('id');
                $table->string('name', 150)->unique();
                $table->string('title');
                $table->string('post_type');
                $table->string('status'); // publish/draft/opened/closed/private
                $table->text('content');
                $table->integer('user_id');
                $table->integer('owner_id');
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
        Schema::dropIfExists('posts');
    }
}
