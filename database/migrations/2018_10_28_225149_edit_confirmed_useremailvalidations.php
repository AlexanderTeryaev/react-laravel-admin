<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditConfirmedUseremailvalidations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_email_validations', function (Blueprint $table) {
            $table->dropColumn('confirmed');
            $table->renameColumn('confirmed_ts', 'confirmed_at');
        });
        Schema::table('user_email_validations', function (Blueprint $table) {
            $table->dateTime('confirmed_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_email_validations', function (Blueprint $table) {
            $table->boolean('confirmed')->after('groups_match');
            $table->renameColumn('confirmed_at', 'confirmed_ts');
        });
    }
}
