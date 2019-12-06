<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email', function ( Blueprint $table ) {
            $table->bigIncrements('id');
            $table->integer('email_id');
            $table->string('nguoinhan', 100);
            $table->tinyInteger('status')->default(0)->comment('1: Đã gửi, 0: Chưa gửi, -1: Xóa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email');
    }
}
