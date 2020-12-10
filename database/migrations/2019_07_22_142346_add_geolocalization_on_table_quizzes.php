<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeolocalizationOnTableQuizzes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->float('qz_latitude')->nullable();
            $table->float('qz_longitude')->nullable();
            $table->boolean('is_geolocalized');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('qz_latitude');
            $table->dropColumn('qz_longitude');
            $table->dropColumn('is_geolocalized');
        });
    }
}
