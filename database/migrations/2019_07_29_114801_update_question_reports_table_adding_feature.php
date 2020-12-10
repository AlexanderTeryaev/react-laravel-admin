<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionReportsTableAddingFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_reports', function (Blueprint $table) {
            $table->string('review')->nullable();
        });
        DB::statement("ALTER TABLE question_reports MODIFY COLUMN status ENUM('pending', 'resolved', 'closed')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_reports', function (Blueprint $table) {
            $table->dropColumn('review');
        });
        DB::statement("ALTER TABLE question_reports MODIFY COLUMN status ENUM('pending', 'resolved')");
    }
}
