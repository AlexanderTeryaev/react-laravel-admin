<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDateInUserAnswersModelToAnsweredAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('user_answers', 'date')) {
            Schema::table('user_answers', function (Blueprint $table) {
                $table->string('answered_at')->after('result');
            });

            DB::table('user_answers')->update([
                'answered_at' => DB::raw('date')
            ]);

            Schema::table('user_answers', function (Blueprint $table) {
               $table->dropColumn('date');
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
        if (Schema::hasColumn('user_answers', 'answered_at')) {
            Schema::table('user_answers', function (Blueprint $table) {
                $table->renameColumn('answered_at', 'date');
            });
        }
    }
}
