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
            $table->string('nguoigui', 100);
            $table->text('tieude');
            $table->text('noidung');
            $table->text('files')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1: Đã gửi, 0: Chưa gửi');
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
