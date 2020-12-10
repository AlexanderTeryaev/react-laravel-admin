<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserAlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_alerts', function (Blueprint $table) {
            $table->dropColumn('body');
            $table->dropColumn('additional_data');
        });
        Schema::table('user_alerts', function (Blueprint $table) {
            $table->string('body')->nullable()->after('subject');
            $table->json('additional_data')->nullable()->after('body');
            $table->boolean('seen')->default(false)->after('additional_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_alerts', function (Blueprint $table) {
            $table->dropColumn('seen');
        });
    }
}
