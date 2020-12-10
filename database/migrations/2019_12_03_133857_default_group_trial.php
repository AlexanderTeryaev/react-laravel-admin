<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultGroupTrial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $default_group = \App\Group::findOrFail(1);
        $default_group->trial_ends_at = now()->addYears(10);
        $default_group->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $default_group = \App\Group::findOrFail(1);
        $default_group->trial_ends_at = null;
        $default_group->save();
    }
}
