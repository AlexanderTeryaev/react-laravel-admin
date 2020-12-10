<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateFaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('faq')) {
            Schema::create('faq', function (Blueprint $table) {
                $table->increments('id');
                $table->text('question');
                $table->text('answer');
                $table->softDeletes();
                $table->timestamps();
            });
        }
        Permission::create(['name' => 'view faq']);
        Permission::create(['name' => 'create faq']);
        Permission::create(['name' => 'edit faq']);
        Permission::create(['name' => 'delete faq']);

        $role = Role::findByName('root');
        $role->givePermissionTo([
            'view faq',
            'create faq',
            'edit faq',
            'delete faq',
        ]);

        $role = Role::findByName('it');
        $role->givePermissionTo([
            'view faq',
            'create faq',
            'edit faq',
            'delete faq',
        ]);

        $role = Role::findByName('management');
        $role->givePermissionTo([
            'view faq',
            'create faq',
            'edit faq',
            'delete faq',
        ]);

        $role = Role::findByName('content-manager');
        $role->givePermissionTo([
            'view faq',
            'create faq',
            'edit faq',
            'delete faq',
        ]);

        $role = Role::findByName('noob');
        $role->givePermissionTo([
            'view faq',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq');
        $role = Role::findByName('root');
        $role->revokePermissionTo(['view faq',
            'create faq',
            'edit faq',
            'delete faq']);

        $role = Role::findByName('it');
        $role->revokePermissionTo(['view faq',
            'create faq',
            'edit faq',
            'delete faq']);

        $role = Role::findByName('management');
        $role->revokePermissionTo(['view faq',
            'create faq',
            'edit faq',
            'delete faq']);

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo(['view faq',
            'create faq',
            'edit faq',
            'delete faq']);
        $role = Role::findByName('noob');
        $role->revokePermissionTo(['view faq']);
    }
}
