<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class QuestionDeletePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::findByName('root');
        $role->givePermissionTo('delete question');

        $role = Role::findByName('it');
        $role->givePermissionTo('delete question');

        $role = Role::findByName('management');
        $role->givePermissionTo('delete question');

        $role = Role::findByName('content-manager');
        $role->givePermissionTo('delete question');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::findByName('root');
        $role->revokePermissionTo('delete question');

        $role = Role::findByName('it');
        $role->revokePermissionTo('delete question');

        $role = Role::findByName('management');
        $role->revokePermissionTo('delete question');

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo('delete question');
    }
}
