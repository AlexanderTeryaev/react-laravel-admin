<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_trainings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_author_id');
            $table->text('name');
            $table->text('subtitle');
            $table->string('image_url');
            $table->enum('difficulty', ['EASY', 'MEDIUM', 'HARD']);
            $table->json('tags');
            $table->longText('description');
            $table->integer('price');
            $table->boolean('is_published');
            $table->timestamps();


            $table->foreign('shop_author_id')
                ->references('id')->on('shop_authors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_trainings');
    }
}
