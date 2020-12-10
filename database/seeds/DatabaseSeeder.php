<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GroupsTableSeeder::class);
        $this->call(AuthorsTableSeeder::class);

        foreach (\App\Group::all() as $group)
        {
            $group->authors()->each(function ($author) use ($group){
                $author->quizzes()->saveMany(factory(App\Quizz::class, mt_rand(3, 10))->make(['group_id' => $group->id]));
                $author->quizzes()->each(function ($quizz) use ($group, $author){
                    $quizz->questions()->saveMany(factory(App\Question::class, mt_rand(5, 10))->make(['group_id' => $group->id]));
                });
            });
            $group->populations()->saveMany(factory(App\GroupPopulation::class, rand(1, 3))->make());
        }

        $this->call(CategoriesTableSeeder::class);
        $this->call(FeaturedTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserAnswerTableSeeder::class);
        $this->call(UserSubscriptionsTableSeeder::class);

        $this->call(ShopAuthorTableSeeder::class);
        $this->call(ShopTrainingTableSeeder::class);
        $this->call(ShopQuizzTableSeeder::class);
        $this->call(ShopQuestionTableSeeder::class);



    }
}
