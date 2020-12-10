<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('authors'))
        {
            Schema::create('authors', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->string('pic_url');
                $table->string('name');
                $table->string('function');
                $table->longText('description');
                $table->string('fb_link');
                $table->string('twitter_link');
                $table->string('website_link');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
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
        Schema::dropIfExists('authors');
    }
}
