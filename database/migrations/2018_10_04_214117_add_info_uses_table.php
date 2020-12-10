<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoUsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('mood')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->boolean('promode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('mood');
            $table->dropColumn('age');
            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropColumn('promode');
        });
    }
}
