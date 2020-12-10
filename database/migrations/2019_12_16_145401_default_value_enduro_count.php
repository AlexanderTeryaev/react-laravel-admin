<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultValueEnduroCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE quizzes MODIFY COLUMN enduro_limit int(10) DEFAULT 0 ");
        \Illuminate\Support\Facades\DB::table('quizzes')->whereNull('enduro_limit')->update(['enduro_limit' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE quizzes MODIFY COLUMN enduro_limit int(10) DEFAULT NULL");
        });
    }
}
