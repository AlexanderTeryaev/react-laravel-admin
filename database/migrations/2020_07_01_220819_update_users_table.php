<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('DROP INDEX users_device_id_unique ON users');
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY device_id VARCHAR(191) null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY device_id VARCHAR(191) not null');
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ADD CONSTRAINT users_device_id_unique UNIQUE (device_id)');
        });
    }
}
