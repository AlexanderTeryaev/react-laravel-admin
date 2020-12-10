<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users'))
        {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('current_group');
                $table->string('device_id')->unique();
                $table->ipAddress('ip');
                $table->timestamp('last_action')->useCurrent();
                $table->enum('curr_os', ['n/a', 'ios', 'android']);
                $table->string('curr_app_version')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('current_group')
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
        Schema::dropIfExists('users');
    }
}
