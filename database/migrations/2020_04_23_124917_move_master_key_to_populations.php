<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MoveMasterKeyToPopulations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_populations', function (Blueprint $table) {
            $table->string('master_key', 5)->after('description');
        });

        foreach (\App\Group::all() as $group){
            if ($group->populations()->count() == 0)
                $group->populations()->create([
                    'name' => 'Default population',
                    'description' => 'Default population',
                    'master_key' => Str::lower(Str::random(5)) // Improve to avoid duplicates
                ]);

            $pop = $group->populations()->first();
            $pop->update(['master_key' => Str::lower($group->master_key)]);
        }

        foreach (\App\GroupPopulation::all() as $pop) {
            if (!$pop->master_key)
                $pop->update(['master_key' => Str::lower(Str::random(5))]);
        }

        Schema::table('group_populations', function (Blueprint $table) {
            $table->string('master_key')->unique()->change();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('master_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {
            $table->string('master_key')->after('name')->unique();
        });
    }
}
