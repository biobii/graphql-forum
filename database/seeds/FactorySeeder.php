<?php

use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 3)->create();
        factory(App\Models\Tag::class, 3)->create();
        factory(App\Models\Forum::class, 5)->create();
        factory(App\Models\Comment::class, 10)->create();
    }
}
