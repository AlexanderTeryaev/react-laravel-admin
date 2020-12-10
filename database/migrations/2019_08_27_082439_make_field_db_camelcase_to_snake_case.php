<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeFieldDbCamelcaseToSnakeCase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('user_answers', 'isEnduro')) {
            Schema::table('user_answers', function (Blueprint $table) {
                $table->renameColumn('isEnduro', 'is_enduro');
            });
        }
        if (Schema::hasColumn('user_alerts', 'additionalData')) {
            Schema::table('user_alerts', function (Blueprint $table) {
                $table->renameColumn('additionalData', 'additional_data');
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
        if (Schema::hasColumn('user_answers', 'is_enduro')) {
            Schema::table('user_answers', function (Blueprint $table) {
                $table->renameColumn('is_enduro', 'isEnduro');
            });
        }
        if (Schema::hasColumn('user_alerts', 'additional_data')) {
            Schema::table('user_alerts', function (Blueprint $table) {
                $table->renameColumn('additional_data', 'additionalData');
            });
        }
    }
}
