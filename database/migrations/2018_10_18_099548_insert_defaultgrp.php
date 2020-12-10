<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultgrp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('groups')->insert(
            array(
                'name' => 'Marmelade',
                'master_key' => 'BLC18',
                'logo_url' => '1539099913.png',
                'enabled' => true,
                'description' => 'Marmelade official group',
                'private' => false,
                'add_by_email_whitelist' => false,
                'add_by_email_domain' => false,
                'created_at' => \Carbon\Carbon::today()->toDateString(),
                'updated_at' => \Carbon\Carbon::today()->toDateString()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('groups')->truncate();
    }
}
