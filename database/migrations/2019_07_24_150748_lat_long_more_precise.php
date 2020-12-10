<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LatLongMorePrecise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE quizzes MODIFY qz_latitude DOUBLE(16,5)');
        DB::statement('ALTER TABLE quizzes MODIFY qz_longitude DOUBLE(16,5)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->float('qz_latitude')->change();
            $table->float('qz_longitude')->change();
        });
    }
}
