<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDeleteAtToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_answers', function (Blueprint $table) {
           $table->dropColumn('deleted_at');
        });
        Schema::table('group_emails_whitelist', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('group_email_domains', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('question_reports', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('user_email_validations', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('user_statistics', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('group_emails_whitelist', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('group_email_domains', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('question_reports', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('user_email_validations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('user_groups', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('user_statistics', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
}
