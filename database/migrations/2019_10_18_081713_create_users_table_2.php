<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('users') ) {
            if ( !Schema::hasColumn('users', 'role_ids') ) {
                Schema::table('users',
                    function ( Blueprint $table ) {
                        $table->integer('avatar')->after('name')->default(0);
                        $table->text('role_ids')->after('avatar')->nullable();
                        $table->text('donvi_ids')->after('role_ids')->nullable();
                        $table->integer('donvi_id')->after('donvi_ids')->default(0);
                        $table->tinyInteger('status')->after('donvi_id')->default(1);
                    }
                );
            }
        } else {
            Schema::create('users',
                function ( Blueprint $table ) {
                    $table->bigIncrements('id');
                    $table->string('name', 100);
                    $table->integer('avatar')->after('name')->default(0);
                    $table->text('role_ids')->after('avatar')->nullable();
                    $table->text('donvi_ids')->after('role_ids')->nullable();
                    $table->integer('donvi_id')->after('donvi_ids')->default(0);
                    $table->tinyInteger('status')->after('donvi_id')->default(1);
                    $table->string('email', 100)->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                }
            );
        }
    }
}
