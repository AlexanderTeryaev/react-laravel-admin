<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_training_id');
            $table->unsignedBigInteger('shop_quizz_id');
            $table->unsignedBigInteger('shop_author_id');
            $table->text('question');
            $table->text('good_answer');
            $table->text('bad_answer');
            $table->longText('more');
            $table->string('bg_url');
            $table->enum('difficulty', ['EASY', 'MEDIUM', 'HARD']);


            $table->foreign('shop_quizz_id')
                ->references('id')->on('shop_quizzes')
                ->onDelete('cascade');
            $table->foreign('shop_training_id')
                ->references('id')->on('shop_trainings')
                ->onDelete('cascade');
            $table->foreign('shop_author_id')
                ->references('id')->on('shop_authors')
                ->onDelete('cascade');
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
        Schema::dropIfExists('shop_questions');
    }
}
