<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValueEnumUsergroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_groups', function (Blueprint $table) {
            DB::statement("ALTER TABLE user_groups MODIFY COLUMN method ENUM('default','public','email', 'master_key')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_groups', function (Blueprint $table) {
            DB::statement("ALTER TABLE user_groups MODIFY COLUMN method ENUM('default','public','email_wl','email_domain')");
        });
    }
}
