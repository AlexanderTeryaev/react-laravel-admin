<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddPermRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create(['name' => 'view group']);
        Permission::create(['name' => 'create group']);
        Permission::create(['name' => 'edit group']);
        Permission::create(['name' => 'delete group']);

        Permission::create(['name' => 'view quizz']);
        Permission::create(['name' => 'create quizz']);
        Permission::create(['name' => 'edit quizz']);
        Permission::create(['name' => 'delete quizz']);

        Permission::create(['name' => 'view question']);
        Permission::create(['name' => 'create question']);
        Permission::create(['name' => 'edit question']);
        Permission::create(['name' => 'delete question']);

        Permission::create(['name' => 'view author']);
        Permission::create(['name' => 'create author']);
        Permission::create(['name' => 'edit author']);
        Permission::create(['name' => 'delete author']);

        Permission::create(['name' => 'view category']);
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);

        Permission::create(['name' => 'view featured']);
        Permission::create(['name' => 'create featured']);
        Permission::create(['name' => 'edit featured']);
        Permission::create(['name' => 'delete featured']);

        Permission::create(['name' => 'view tag']);
        Permission::create(['name' => 'create tag']);
        Permission::create(['name' => 'edit tag']);
        Permission::create(['name' => 'delete tag']);

        Permission::create(['name' => 'view admin']);
        Permission::create(['name' => 'create admin']);
        Permission::create(['name' => 'edit admin']);
        Permission::create(['name' => 'delete admin']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        $role = Role::create(['name' => 'root']);
        $role->givePermissionTo([
            'view group',
            'create group',
            'edit group',
            'delete group',
            
            'view quizz',
            'create quizz',
            'edit quizz',
            'delete quizz',
            
            'view question',
            'create question',
            'edit question',
            'delete question',
            
            'view author',
            'create author',
            'edit author',
            'delete author',

            'view category',
            'create category',
            'edit category',
            'delete category',

            'view featured',
            'create featured',
            'edit featured',
            'delete featured',

            'view tag',
            'create tag',
            'edit tag',
            'delete tag',

            'view admin',
            'create admin',
            'edit admin',
            'delete admin',

            'view user',
            'create user',
            'edit user',
            'delete user',
        ]);

        $role = Role::create(['name' => 'it']);
        $role->givePermissionTo([
            'view group',
            'create group',
            'edit group',
            'delete group',

            'view quizz',
            'create quizz',
            'edit quizz',
            'delete quizz',

            'view question',
            'create question',
            'edit question',
            'delete question',

            'view author',
            'create author',
            'edit author',
            'delete author',

            'view category',
            'create category',
            'edit category',
            'delete category',

            'view featured',
            'create featured',
            'edit featured',
            'delete featured',

            'view tag',
            'create tag',
            'edit tag',
            'delete tag',

            'view admin',

            'view user',
            'create user',
            'edit user',
            'delete user',
        ]);

        $role = Role::create(['name' => 'management']);
        $role->givePermissionTo([
            'view group',
            'create group',
            'edit group',
            'delete group',

            'view quizz',
            'create quizz',
            'edit quizz',
            'delete quizz',

            'view question',
            'create question',
            'edit question',
            'delete question',

            'view author',
            'create author',
            'edit author',
            'delete author',

            'view category',
            'create category',
            'edit category',
            'delete category',

            'view featured',
            'create featured',
            'edit featured',
            'delete featured',

            'view tag',
            'create tag',
            'edit tag',
            'delete tag',

            'view admin',

            'view user'
        ]);

        $role = Role::create(['name' => 'content-manager']);
        $role->givePermissionTo([
            'view group',
            'create group',
            'edit group',
            'delete group',

            'view quizz',
            'create quizz',
            'edit quizz',
            'delete quizz',

            'view question',
            'create question',
            'edit question',
            'delete question',

            'view author',
            'create author',
            'edit author',
            'delete author',

            'view category',
            'create category',
            'edit category',
            'delete category',

            'view featured',
            'create featured',
            'edit featured',
            'delete featured',

            'view tag',
            'create tag',
            'edit tag',
            'delete tag',

            'view user'
        ]);

        $role = Role::create(['name' => 'noob']);
        $role->givePermissionTo([
            'view group',
            'view quizz',
            'view question',
            'view author',
            'view category',
            'view featured',
            'view tag',
            'view user',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
    }
}
