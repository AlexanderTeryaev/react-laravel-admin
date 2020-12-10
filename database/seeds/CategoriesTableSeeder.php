<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
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
            factory(App\Category::class, mt_rand(2, 5))->create(['group_id' => $group->id])->each(function ($category) use($group){
                $quizzes = \App\Quizz::where('group_id', $group->id)->get()->random(2)->pluck('id');
                $category->quizzes()->sync($quizzes);
            });
        }
    }
}
