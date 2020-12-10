<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class FeaturedDeletePremission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::findByName('root');
        $role->givePermissionTo('delete featured');

        $role = Role::findByName('it');
        $role->givePermissionTo('delete featured');

        $role = Role::findByName('management');
        $role->givePermissionTo('delete featured');

        $role = Role::findByName('content-manager');
        $role->givePermissionTo('delete featured');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::findByName('root');
        $role->revokePermissionTo('delete featured');

        $role = Role::findByName('it');
        $role->revokePermissionTo('delete featured');

        $role = Role::findByName('management');
        $role->revokePermissionTo('delete featured');

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo('delete featured');
    }
}
