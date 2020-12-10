<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('function');
            $table->longText('description');
            $table->string('pic_url');
            $table->text('fb_link')->nullable();
            $table->text('twitter_link')->nullable();
            $table->text('website_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_authors');
    }
}
