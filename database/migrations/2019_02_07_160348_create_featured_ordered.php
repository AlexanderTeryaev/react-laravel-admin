<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedOrdered extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('featured_ordered'))
        {
            Schema::create('featured_ordered', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->unsignedInteger('featured_id');
                $table->unsignedInteger('order_id');
                $table->timestamps();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
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
        Schema::dropIfExists('featured_ordered');
    }
}
