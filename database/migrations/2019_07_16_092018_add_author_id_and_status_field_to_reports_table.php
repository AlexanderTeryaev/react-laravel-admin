<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorIdAndStatusFieldToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_reports', function (Blueprint $table) {
            $table->integer('author_id')->nullable();
            $table->enum('status', ['pending', 'resolved'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_reports', function (Blueprint $table) {
            $table->dropColumn('author_id');
            $table->dropColumn('status');
        });
    }
}
