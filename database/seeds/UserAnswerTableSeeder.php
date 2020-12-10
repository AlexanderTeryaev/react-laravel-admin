<?php

use Illuminate\Database\Seeder;

class UserAnswerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\User::all() as $user)
        {
            foreach ($user->groups as $group)
            {
                $questions = \App\Question::where('group_id', $group->id)->get()->random(10)->pluck('id');
                foreach ($questions as $question)
                    factory(App\UserAnswer::class)->create([
                        'group_id' => $group->id,
                        'user_id' => $user->id,
                        'question_id' => $question,
                    ]);

                // Enduro answers
                if (mt_rand(0, 1))
                {
                    $quizz = \App\Quizz::where('group_id', $group->id)->get()->random();
                    $questions = \App\Question::where('quizz_id', $quizz->id)->get()->pluck('id');
                    foreach ($questions as $question)
                        factory(App\UserAnswer::class)->create([
                            'group_id' => $group->id,
                            'user_id' => $user->id,
                            'question_id' => $question,
                            'is_enduro' => 1,
                            'answered_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                }
            }
        }
    }
}
