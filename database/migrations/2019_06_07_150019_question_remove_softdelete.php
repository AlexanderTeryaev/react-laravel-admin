<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestionRemoveSoftdelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            DB::statement("ALTER TABLE questions MODIFY COLUMN `more` LONGTEXT AFTER good");
            DB::statement("ALTER TABLE `questions` MODIFY COLUMN `author_id` INT(10) UNSIGNED NOT NULL AFTER `quizz_id`;
");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
}
