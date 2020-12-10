<?php

use Illuminate\Database\Seeder;

class ShopAuthorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\ShopAuthor::class, 10)->create();
    }
}
