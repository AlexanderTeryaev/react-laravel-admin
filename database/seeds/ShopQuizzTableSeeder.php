<?php

use Illuminate\Database\Seeder;

class ShopQuizzTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\ShopTraining::all() as $training){
            factory(\App\ShopQuizz::class, mt_rand(2, 5))->create([
                'shop_author_id' => $training->author->id,
                'shop_training_id' => $training->id
            ]);
        }
    }
}
