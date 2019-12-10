<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailReceiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_receive', function ( Blueprint $table ) {
            $table->bigIncrements('id');
            $table->integer('email_id');
            $table->string('nguoinhan', 100);
            $table->tinyInteger('status')->default(1)->comment('0: Chưa đọc, 1: Đã đọc, -1: Đã xóa');
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
        Schema::dropIfExists('email_receive');
    }
}
