<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopImportQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('shop_question_imports'))
        {
            Schema::create('shop_question_imports', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('admin_id')->unsigned()->index();
                $table->unsignedBigInteger('shop_question_id')->unsigned()->index()->nullable();
                $table->unsignedBigInteger('shop_quizz_id')->unsigned()->index();
                $table->unsignedBigInteger('shop_author_id')->unsigned()->index();
                $table->unsignedBigInteger('shop_training_id')->unsigned()->index();
                $table->string('question');
                $table->string('good_answer');
                $table->string('bad_answer');
                $table->text('more');
                $table->enum('good', ['ANSWER_1', 'ANSWER_2']);
                $table->string('bg_url');
                $table->enum('difficulty', ['EASY', 'MEDIUM', 'HARD']);
                $table->boolean('imported');

                $table->foreign('admin_id')
                    ->references('id')
                    ->on('admins')
                    ->onDelete('cascade');

                $table->foreign('shop_quizz_id')
                    ->references('id')
                    ->on('shop_quizzes')
                    ->onDelete('cascade');

                $table->foreign('shop_author_id')
                    ->references('id')
                    ->on('shop_authors')
                    ->onDelete('cascade');

                $table->foreign('shop_training_id')
                    ->references('id')
                    ->on('shop_trainings')
                    ->onDelete('cascade');

                $table->foreign('shop_question_id')
                    ->references('id')
                    ->on('shop_questions')
                    ->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_question_imports');
    }
}
