<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class DeleteGroupPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::findByName('root');
        $role->givePermissionTo('delete group');

        $role = Role::findByName('it');
        $role->givePermissionTo('delete group');

        $role = Role::findByName('management');
        $role->givePermissionTo('delete group');

        $role = Role::findByName('content-manager');
        $role->givePermissionTo('delete group');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::findByName('root');
        $role->revokePermissionTo('delete group');

        $role = Role::findByName('it');
        $role->revokePermissionTo('delete group');

        $role = Role::findByName('management');
        $role->revokePermissionTo('delete group');

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo('delete group');
    }
}
