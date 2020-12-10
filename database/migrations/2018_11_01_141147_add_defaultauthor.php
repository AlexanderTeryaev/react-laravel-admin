<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultauthor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            DB::table('authors')->insert(
                array(
                    'group_id' => 1,
                    'pic_url' => '543454354543.png',
                    'name' => 'Marmelade',
                    'function' => 'Marmelade',
                    'description' => 'Group officiel Marmelade',
                    'fb_link' => 'https://www.facebook.com/marmeladeapp/',
                    'twitter_link' => 'https://twitter.com/Marmeladeapp',
                    'website_link' => 'https://marmelade-app.fr/',
                    'created_at' => \Carbon\Carbon::today()->toDateString(),
                    'updated_at' => \Carbon\Carbon::today()->toDateString()
                )
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            DB::table('authors')->truncate();
        });
    }
}
