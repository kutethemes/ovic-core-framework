<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'      => 'Super Admin',
            'email'     => 'admin@laravel.com',
            'password'  => Hash::make('12345678'),
            'status'    => '3',
            'avatar'    => '0',
            'role_ids'  => '0',
            'donvi_ids' => '0',
            'donvi_id'  => '0',
            'canhan_id' => '0',
        ]);
    }
}
