<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateQuizzTagsRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('quizz_tags');
        Schema::dropIfExists('tags');
        Schema::table('quizzes', function (Blueprint $table) {
            $table->json('tags');
        });
        $role = Role::findByName('root');
        $role->revokePermissionTo(['view tag',
            'create tag',
            'edit tag',
            'delete tag',]);

        $role = Role::findByName('it');
        $role->revokePermissionTo(['view tag',
            'create tag',
            'edit tag',
            'delete tag',]);

        $role = Role::findByName('management');
        $role->revokePermissionTo(['view tag',
            'create tag',
            'edit tag',
            'delete tag',]);

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo(['view tag',
            'create tag',
            'edit tag',
            'delete tag',]);
        $role = Role::findByName('noob');
        $role->revokePermissionTo(['view tag']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('tags'))
        {
            Schema::create('tags', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id');
                $table->string('name');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');
            });

            Schema::create('quizz_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('quizz_id')->unsigned()->index();
                $table->integer('tags_id')->unsigned()->index();
                $table->foreign('quizz_id')
                    ->references('id')
                    ->on('quizzes')
                    ->onDelete('cascade');
                $table->foreign('tags_id')
                    ->references('id')
                    ->on('tags')
                    ->onDelete('cascade');
            });
        }
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
        Permission::create(['name' => 'view tag']);
        Permission::create(['name' => 'create tag']);
        Permission::create(['name' => 'edit tag']);
        Permission::create(['name' => 'delete tag']);

        $role = Role::findByName('root');
        $role->givePermissionTo([
            'view tag',
            'create tag',
            'edit tag',
            'delete tag',
        ]);

        $role = Role::findByName('it');
        $role->givePermissionTo([
            'view tag',
            'create tag',
            'edit tag',
            'delete tag',
        ]);

        $role = Role::findByName('management');
        $role->givePermissionTo([
            'view tag',
            'create tag',
            'edit tag',
            'delete tag',
        ]);

        $role = Role::findByName('content-manager');
        $role->givePermissionTo([
            'view tag',
            'create tag',
            'edit tag',
            'delete tag',
        ]);

        $role = Role::findByName('noob');
        $role->givePermissionTo([
            'view tag',
        ]);
    }
}

