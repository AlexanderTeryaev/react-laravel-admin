<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('press')) {
            Schema::create('press', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('link')->nullable();
                $table->string('image_url');
                $table->dateTime('published_at');
                $table->softDeletes();
                $table->timestamps();
            });
        }
        Permission::create(['name' => 'view press']);
        Permission::create(['name' => 'create press']);
        Permission::create(['name' => 'edit press']);
        Permission::create(['name' => 'delete press']);

        $role = Role::findByName('root');
        $role->givePermissionTo([
            'view press',
            'create press',
            'edit press',
            'delete press',
        ]);

        $role = Role::findByName('it');
        $role->givePermissionTo([
            'view press',
            'create press',
            'edit press',
            'delete press',
        ]);

        $role = Role::findByName('management');
        $role->givePermissionTo([
            'view press',
            'create press',
            'edit press',
            'delete press',
        ]);

        $role = Role::findByName('content-manager');
        $role->givePermissionTo([
            'view press',
            'create press',
            'edit press',
            'delete press',
        ]);

        $role = Role::findByName('noob');
        $role->givePermissionTo([
            'view press',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('press');
        $role = Role::findByName('root');
        $role->revokePermissionTo(['view press',
            'create press',
            'edit press',
            'delete press',]);

        $role = Role::findByName('it');
        $role->revokePermissionTo(['view press',
            'create press',
            'edit press',
            'delete press',]);

        $role = Role::findByName('management');
        $role->revokePermissionTo(['view press',
            'create press',
            'edit press',
            'delete press',]);

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo(['view press',
            'create press',
            'edit press',
            'delete press',]);
        $role = Role::findByName('noob');
        $role->revokePermissionTo(['view press']);
    }
}
