<?php

use Illuminate\Database\Seeder;

class ShopQuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\ShopQuizz::all() as $quizz){
            factory(\App\ShopQuestion::class, mt_rand(3, 10))->create([
                'shop_training_id' => $quizz->training->id,
                'shop_quizz_id' => $quizz->id,
                'shop_author_id' => $quizz->author->id,
            ]);
        }
    }
}
