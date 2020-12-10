<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUseremailsvalidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('useremailsvalidation'))
        {
            Schema::create('useremailsvalidation', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->string('email');
                $table->string('code')->unique();
                $table->string('groups_match');
                $table->dateTime('requested');
                $table->boolean('confirmed');
                $table->dateTime('confirmed_ts');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('useremailsvalidation');
    }
}
