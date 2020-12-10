<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateConfigsPermRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create(['name' => 'view config']);
        Permission::create(['name' => 'create config']);
        Permission::create(['name' => 'edit config']);
        Permission::create(['name' => 'delete config']);

        $role = Role::where('name', 'root')->first();
        $role->givePermissionTo([
            'view config',
            'create config',
            'edit config',
            'delete config',
        ]);

        $role = Role::where('name', 'it')->first();
        $role->givePermissionTo([
            'view config',
            'create config',
            'edit config',
            'delete config',
        ]);

        $role = Role::where('name', 'management')->first();
        $role->givePermissionTo([
            'view config',
            'create config',
            'edit config',
            'delete config',
        ]);

        $role = Role::where('name', 'content-manager')->first();
        $role->givePermissionTo([
            'view config',
            'create config',
            'edit config',
            'delete config',
        ]);

        $role = Role::where('name', 'noob')->first();
        $role->givePermissionTo([
            'view config',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}