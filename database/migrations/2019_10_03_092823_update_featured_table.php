<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFeaturedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('featured_ordered');

        Schema::table('featured', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->boolean('is_published')->default(false)->after('order_id');
        });

        DB::table('featured')->update([
            'is_published' => DB::raw('status')
        ]);

        Schema::table('featured', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('featured', function (Blueprint $table) {
            $table->boolean('status')->default('false')->after('order_id');
            $table->dropColumn('is_published');
        });
    }
}
