<?php

use Illuminate\Database\Seeder;

class FeaturedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Group::all() as $group)
        {
            factory(App\Featured::class, mt_rand(2, 5))->create(['group_id' => $group->id])->each(function ($featured) use($group){
                $quizzes = \App\Quizz::where('group_id', $group->id)->get()->random(2)->pluck('id');
                $featured->quizzes()->sync($quizzes);
            });

        }
    }
}
