<?php

use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Group::all() as $group)
            factory(App\Author::class, mt_rand(2, 5))->create(['group_id' => $group->id]);
    }
}
