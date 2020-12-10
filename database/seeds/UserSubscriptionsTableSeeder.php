<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\User::all() as $user)
        {
            foreach ($user->groups as $group)
            {
                $quizzes = \App\Quizz::where('group_id', $group->id)->get()->random(2);
                foreach ($quizzes as $quizz)
                {
                    if (!$user->isSubscribed($quizz->id))
                        factory(App\UserSubscription::class)->create([
                            'group_id' => $group->id,
                            'user_id' => $user->id,
                            'quizz_id' => $quizz->id
                        ]);
                }

            }

        }

    }
}
