<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUserGroupRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        $result = \Illuminate\Support\Facades\DB::table('user_group_rights')->select('group_id', 'user_id', 'created_at', 'updated_at')->get()->toArray();
        $result = array_map(function ($value) {
            return (array)$value;
        }, $result);
        \Illuminate\Support\Facades\DB::table('group_managers')->insert($result);
        Schema::drop('user_group_rights');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_managers');
    }
}
