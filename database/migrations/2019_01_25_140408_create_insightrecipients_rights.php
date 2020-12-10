<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateInsightrecipientsRights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create(['name' => 'view insight_recipient']);
        Permission::create(['name' => 'create insight_recipient']);
        Permission::create(['name' => 'edit insight_recipient']);
        Permission::create(['name' => 'delete insight_recipient']);

        $role = Role::findByName('root');
        $role->givePermissionTo([
            'view insight_recipient',
            'create insight_recipient',
            'edit insight_recipient',
            'delete insight_recipient',
        ]);

        $role = Role::findByName('it');
        $role->givePermissionTo([
            'view insight_recipient',
            'create insight_recipient',
            'edit insight_recipient',
            'delete insight_recipient',
        ]);

        $role = Role::findByName('management');
        $role->givePermissionTo([
            'view insight_recipient',
            'create insight_recipient',
            'edit insight_recipient',
            'delete insight_recipient',
        ]);

        $role = Role::findByName('content-manager');
        $role->givePermissionTo([
            'view insight_recipient',
            'create insight_recipient',
            'edit insight_recipient',
            'delete insight_recipient',
        ]);

        $role = Role::findByName('noob');
        $role->givePermissionTo([
            'view insight_recipient',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
