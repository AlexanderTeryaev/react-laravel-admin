<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameEnableFieldIntoStatusField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function(Blueprint $table) {
            $table->renameColumn('enable', 'status');
        });
        Schema::table('featured', function(Blueprint $table) {
            $table->renameColumn('enable', 'status');
        });
        Schema::table('groups', function(Blueprint $table) {
            $table->renameColumn('enabled', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function(Blueprint $table) {
            $table->renameColumn('status', 'enable');
        });
        Schema::table('enable', function(Blueprint $table) {
            $table->renameColumn('status', 'enable');
        });
        Schema::table('groups', function(Blueprint $table) {
            $table->renameColumn('status', 'enabled');
        });
    }
}
