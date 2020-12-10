<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionGroupEmailWhitelist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create(['name' => 'view group_email_whitelist']);
        Permission::create(['name' => 'create group_email_whitelist']);
        Permission::create(['name' => 'edit group_email_whitelist']);
        Permission::create(['name' => 'delete group_email_whitelist']);

        $role = Role::findByName('root');
        $role->givePermissionTo([
            'view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',
        ]);

        $role = Role::findByName('it');
        $role->givePermissionTo([
            'view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',
        ]);

        $role = Role::findByName('management');
        $role->givePermissionTo([
            'view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',
        ]);

        $role = Role::findByName('content-manager');
        $role->givePermissionTo([
            'view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',
        ]);

        $role = Role::findByName('noob');
        $role->givePermissionTo([
            'view group_email_whitelist',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::findByName('root');
        $role->revokePermissionTo(['view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',]);

        $role = Role::findByName('it');
        $role->revokePermissionTo(['view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',]);

        $role = Role::findByName('management');
        $role->revokePermissionTo(['view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',]);

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo(['view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',]);
        $role = Role::findByName('noob');
        $role->revokePermissionTo(['view group_email_whitelist']);
    }
}
