<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateConfigRights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $view = Permission::findByName('view config');
        $create = Permission::findByName('create config');
        $edit = Permission::findByName('edit config');
        $delete = Permission::findByName('delete config');

        $noob = Role::findByName('noob');
        $content_manager = Role::findByName('content-manager');
        $management = Role::findByName('management');
        $it = Role::findByName('it');
        $root = Role::findByName('root');

        $noob->revokePermissionTo($delete);

        $content_manager->revokePermissionTo($view);
        $content_manager->revokePermissionTo($create);
        $content_manager->revokePermissionTo($edit);
        $content_manager->revokePermissionTo($delete);

        $management->revokePermissionTo($view);
        $management->revokePermissionTo($create);
        $management->revokePermissionTo($edit);
        $management->revokePermissionTo($delete);

        $it->givePermissionTo([$view, $create, $edit, $delete]);
        $root->givePermissionTo([$view, $create, $edit, $delete]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $view = Permission::findByName('view config');
        $create = Permission::findByName('create config');
        $edit = Permission::findByName('edit config');
        $delete = Permission::findByName('delete config');

        $noob = Role::findByName('noob');
        $content_manager = Role::findByName('content-manager');
        $management = Role::findByName('management');
        $it = Role::findByName('it');
        $root = Role::findByName('root');

        $noob->givePermissionTo([$view, $create, $edit, $delete]);
        $content_manager->givePermissionTo([$view, $create, $edit, $delete]);
        $management->givePermissionTo([$view, $create, $edit, $delete]);

        $it->givePermissionTo([$view, $create, $edit, $delete]);
        $root->givePermissionTo([$view, $create, $edit, $delete]);

    }
}
