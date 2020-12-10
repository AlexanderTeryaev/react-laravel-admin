<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupconfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('groupconfigs'))
        {
            Schema::create('groupconfigs', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->string('key');
                $table->string('value');
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
        Schema::dropIfExists('groupconfigs');
    }
}
