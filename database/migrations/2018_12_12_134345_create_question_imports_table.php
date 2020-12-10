<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('question_imports'))
        {
            Schema::create('question_imports', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('group_id')->unsigned()->index();
                $table->integer('quizz_id')->unsigned()->index();
                $table->integer('admin_id')->unsigned()->index();
                $table->string('question');
                $table->string('answer_1');
                $table->string('answer_2');
                $table->enum('good', ['ANSWER_1', 'ANSWER_2']);
                $table->string('bg_url');
                $table->enum('difficulty', ['EASY', 'MEDIUM', 'HARD']);
                $table->boolean('status');
                $table->boolean('imported');
                $table->integer('question_id')->unsigned()->index()->nullable();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');
                $table->foreign('quizz_id')
                    ->references('id')
                    ->on('quizzes')
                    ->onDelete('cascade');
                $table->foreign('admin_id')
                    ->references('id')
                    ->on('admins')
                    ->onDelete('cascade');
                $table->foreign('question_id')
                    ->references('id')
                    ->on('questions')
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
        Schema::dropIfExists('question_imports');
    }
}
