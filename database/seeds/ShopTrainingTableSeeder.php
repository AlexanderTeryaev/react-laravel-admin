<?php

use Illuminate\Database\Seeder;

class ShopTrainingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\ShopAuthor::all() as $author)
        {
            factory(\App\ShopTraining::class, mt_rand(1, 3))->create([
                'shop_author_id' => $author->id
            ]);
        }

    }
}
