<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersLimitPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('users_limit')->default(0)->after('price');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->integer('users_limit')->default(0)->after('add_by_email_domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('users_limit');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->integer('users_limit')->after('price');
        });
    }
}
