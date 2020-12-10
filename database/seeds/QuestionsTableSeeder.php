<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Quizz::all() as $quizz)
            factory(App\Question::class, mt_rand(5, 10))->create([
                'group_id' => $quizz->group_id,
                'quizz_id' => $quizz->id
            ]);
    }
}
