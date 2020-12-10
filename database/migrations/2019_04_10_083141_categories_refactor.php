<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoriesRefactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('categories', 'deleted_at'))
        {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
            if (Schema::hasTable('categories_relation'))
                Schema::drop('categories_relation');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
}
