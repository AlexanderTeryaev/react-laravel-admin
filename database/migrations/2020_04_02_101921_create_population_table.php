<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;
use \App\Group;

class CreatePopulationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_populations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->text('name');
            $table->text('description');
            $table->boolean('is_enabled');
            $table->timestamps();
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');
        });

        Schema::table('user_groups', function (Blueprint $table) {
            $table->unsignedInteger('population_id')->after('group_id')->nullable();
            $table->foreign('population_id')
                ->references('id')
                ->on('group_populations')
                ->onDelete('cascade');
        });

        foreach (Group::all() as $group)
        {
            $population = $group->populations()->create([
                'name' => 'Default',
                'description' => 'Population par défaut',
            ]);

            DB::table('user_groups')->where('group_id', $group->id)->update(['population_id' => $population->id]);
        }

        DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        DB::statement("ALTER TABLE user_groups MODIFY COLUMN population_id int(10) unsigned not null");
        DB::statement("SET FOREIGN_KEY_CHECKS = 1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('populations');
        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropForeign('population_id');
        });
    }
}
