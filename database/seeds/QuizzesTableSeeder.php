<?php

use Illuminate\Database\Seeder;

class QuizzesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Group::all() as $group)
            factory(App\Quizz::class, mt_rand(3, 10))->create([
                'group_id' => $group->id,
                'author_id' => \App\Author::all()->random()->id]
            );
    }
}
