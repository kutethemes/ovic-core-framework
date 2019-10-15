<?php

namespace Ovic\Framework;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define( Post::class,
	function ( Faker $faker ) {
		$title = $faker->realText( 40, 1 );

		return [
			'title'   => $title,
			'name'    => Str::slug( $title ),
			'content' => '',
			'status'  => 'publish',
		];
	}
);
