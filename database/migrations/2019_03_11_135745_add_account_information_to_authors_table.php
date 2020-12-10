<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountInformationToAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('authors', 'email', 'password', 'remember_token')) {
            Schema::table('authors', function (Blueprint $table) {
                 $table->dropColumn('password');
                 $table->dropColumn('email');
                 $table->dropColumn('remember_token');
             });
        }
    }
}
