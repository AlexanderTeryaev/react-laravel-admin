<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->json('features');
            $table->float('price');
            $table->string('plan_id');
            $table->timestamps();
        });

        Permission::create(['name' => 'view plan']);
        Permission::create(['name' => 'create plan']);
        Permission::create(['name' => 'edit plan']);
        Permission::create(['name' => 'delete plan']);

        $role = Role::findByName('root');
        $role->givePermissionTo([
            'view plan',
            'create plan',
            'edit plan',
            'delete plan',
        ]);

        $role = Role::findByName('it');
        $role->givePermissionTo([
            'view plan',
            'create plan',
            'edit plan',
            'delete plan',
        ]);

        $role = Role::findByName('management');
        $role->givePermissionTo([
            'view plan',
            'create plan',
            'edit plan',
            'delete plan',
        ]);

        $role = Role::findByName('content-manager');
        $role->givePermissionTo([
            'view plan',
            'create plan',
            'edit plan',
            'delete plan',
        ]);

        $role = Role::findByName('noob');
        $role->givePermissionTo([
            'view plan',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
        $role = Role::findByName('root');
        $role->revokePermissionTo(['view plan',
            'create plan',
            'edit plan',
            'delete plan',]);

        $role = Role::findByName('it');
        $role->revokePermissionTo(['view plan',
            'create plan',
            'edit plan',
            'delete plan',]);

        $role = Role::findByName('management');
        $role->revokePermissionTo(['view plan',
            'create plan',
            'edit plan',
            'delete plan',]);

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo(['view plan',
            'create plan',
            'edit plan',
            'delete plan',]);
        $role = Role::findByName('noob');
        $role->revokePermissionTo(['view plan']);
    }
}
