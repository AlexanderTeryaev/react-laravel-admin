<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResolvedByReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_reports', function (Blueprint $table) {
            $table->unsignedInteger('admin_id')->after('resolved')->nullable();
            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');
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
            $table->dropForeign('question_reports_admin_id_foreign');
            $table->dropColumn(['admin_id']);
        });
    }
}
