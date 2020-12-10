<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tags'))
        {
            Schema::create('tags', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->string('name');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');
            });

            Schema::create('quizz_tags', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('quizz_id')->unsigned()->index();
               $table->integer('tags_id')->unsigned()->index();
               $table->foreign('quizz_id')
                   ->references('id')
                   ->on('quizzes')
                   ->onDelete('cascade');
                $table->foreign('tags_id')
                    ->references('id')
                    ->on('tags')
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
        Schema::dropIfExists('tags');
        Schema::dropIfExists('quizz_tags');
    }
}
