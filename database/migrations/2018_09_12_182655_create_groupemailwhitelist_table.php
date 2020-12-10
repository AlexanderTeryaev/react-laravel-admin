<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupemailwhitelistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('groupemailwhitelist'))
        {
            Schema::create('groupemailwhitelist', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->string('email');
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
        Schema::dropIfExists('groupemailwhitelist');
    }
}
