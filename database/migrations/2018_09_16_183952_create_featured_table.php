<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('featured'))
        {
            Schema::create('featured', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->string('name');
                $table->string('pic_url');
                $table->text('description');
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');
            });

            Schema::create('featured_quizz', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('quizz_id')->unsigned()->index();
                $table->integer('featured_id')->unsigned()->index();
                $table->foreign('quizz_id')
                    ->references('id')
                    ->on('quizzes')
                    ->onDelete('cascade');
                $table->foreign('featured_id')
                    ->references('id')
                    ->on('featured')
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
        Schema::dropIfExists('featured');
    }
}
