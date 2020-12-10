<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('questions'))
        {
            Schema::create('questions', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->unsignedInteger('quizz_id');
                $table->string('question');
                $table->string('answer_1');
                $table->string('answer_2');
                $table->enum('good', ['ANSWER_1', 'ANSWER_2']);
                $table->string('bg_url');
                $table->enum('difficulty', ['EASY', 'MEDIUM', 'HARD']);
                $table->boolean('status');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');
                $table->foreign('quizz_id')
                    ->references('id')
                    ->on('quizzes')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
}
