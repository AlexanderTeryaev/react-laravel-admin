<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('groups'))
        {
            Schema::create('groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('master_key')->unique();
                $table->string('logo_url');
                $table->boolean('enabled')->default(true);
                $table->integer('questions_limit');
                $table->text('description');
                $table->boolean('private')->default(true);
                $table->boolean('add_by_email_whitelist')->default(false);
                $table->boolean('add_by_email_domain')->default(false);
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('groups');
    }
}