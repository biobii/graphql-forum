<?php

use App\Models\Tag;
use App\Models\User;
use App\Models\Forum;
use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(Tag::class, function (Faker $faker) {
    return ['name' => $faker->word];
});

$factory->define(Forum::class, function (Faker $faker) {
    $title = $faker->sentence;
    $slug = str_slug($title, '-');

    return [
        'user_id' => User::inRandomOrder()->first()->id,
        'title' => $title,
        'slug' => $slug,
        'body' => $faker->paragraph
    ];
});

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'user_id' => User::inRandomOrder()->first()->id,
        'forum_id' => Forum::inRandomOrder()->first()->id,
        'comment' => $faker->paragraph
    ];
});