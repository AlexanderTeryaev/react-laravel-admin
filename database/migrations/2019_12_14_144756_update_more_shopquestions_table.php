<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMoreShopquestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_questions', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE shop_questions MODIFY COLUMN more LONGTEXT ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_questions', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE shop_questions MODIFY COLUMN more LONGTEXT NOT NULL");
        });
    }
}
