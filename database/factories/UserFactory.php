<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'language' => 'es',
    ];
});

$factory->define(App\Post::class, function (Faker $faker) {

    $moderators = [13, 5, 4];
    //$langs = collect(['en','es']);
    
    return [
        'title' => json_encode(['en' => $faker->sentence]),
        'content' => json_encode(['en' => $faker->paragraphs(2, true)]),
        'user_id' => $moderators[array_rand($moderators)]
    ];
});

$factory->define(App\Comment::class, function (Faker $faker) {
    $users = [19, 20, 21, 22];
    return [
        'text' => $faker->sentences(rand(1, 3), true),
        'parent_id' => null,
        'lft' => 1,
        'rgt' => 2,
        'depth' => 0,
        'user_id' => $users[array_rand($users)]
    ];
});

$factory->state(App\Comment::class, 'post', function ($faker) {
    return [
        'commentable_type' => 'post',
        'commentable_id' => function () {
            return factory(Post::class)->states('post')->create()->id;
        },
    ];
});



$factory->define(App\Image::class, function (Faker\Generator $faker) {
    return [
        'filename' => 'dummy' . $faker->fileExtension,
        'mime' => $faker->mimeType,
        'description' => $faker->sentence,
    ];
});
