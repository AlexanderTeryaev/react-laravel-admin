<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthorsAddOnboardedCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('authors', 'remember_token'))
        {
            Schema::table('authors', function (Blueprint $table) {
                $table->boolean('onboarded')->default(false);
                $table->dropColumn('remember_token');
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
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('onboarded');
        });
    }
}
