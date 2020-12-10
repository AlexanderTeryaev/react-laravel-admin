<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddUserPermRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // user permissions
        Permission::create(['guard_name' => 'user', 'name' => 'view group']);
        Permission::create(['guard_name' => 'user', 'name' => 'create group']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit group']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete group']);

        Permission::create(['guard_name' => 'user', 'name' => 'view quizz']);
        Permission::create(['guard_name' => 'user', 'name' => 'create quizz']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit quizz']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete quizz']);

        Permission::create(['guard_name' => 'user', 'name' => 'view question']);
        Permission::create(['guard_name' => 'user', 'name' => 'create question']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit question']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete question']);

        Permission::create(['guard_name' => 'user', 'name' => 'view author']);
        Permission::create(['guard_name' => 'user', 'name' => 'create author']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit author']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete author']);

        Permission::create(['guard_name' => 'user', 'name' => 'view category']);
        Permission::create(['guard_name' => 'user', 'name' => 'create category']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit category']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete category']);

        Permission::create(['guard_name' => 'user', 'name' => 'view featured']);
        Permission::create(['guard_name' => 'user', 'name' => 'create featured']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit featured']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete featured']);

        Permission::create(['guard_name' => 'user', 'name' => 'view tag']);
        Permission::create(['guard_name' => 'user', 'name' => 'create tag']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit tag']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete tag']);

        Permission::create(['guard_name' => 'user', 'name' => 'view admin']);
        Permission::create(['guard_name' => 'user', 'name' => 'create admin']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit admin']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete admin']);

        Permission::create(['guard_name' => 'user', 'name' => 'view user']);
        Permission::create(['guard_name' => 'user', 'name' => 'create user']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit user']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete user']);

        Permission::create(['guard_name' => 'user', 'name' => 'view config']);
        Permission::create(['guard_name' => 'user', 'name' => 'create config']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit config']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete config']);

        Permission::create(['guard_name' => 'user', 'name' => 'view faq']);
        Permission::create(['guard_name' => 'user', 'name' => 'create faq']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit faq']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete faq']);

        Permission::create(['guard_name' => 'user', 'name' => 'view group_email_domains']);
        Permission::create(['guard_name' => 'user', 'name' => 'create group_email_domains']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit group_email_domains']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete group_email_domains']);

        Permission::create(['guard_name' => 'user', 'name' => 'view group_email_whitelist']);
        Permission::create(['guard_name' => 'user', 'name' => 'create group_email_whitelist']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit group_email_whitelist']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete group_email_whitelist']);

        Permission::create(['guard_name' => 'user', 'name' => 'view press']);
        Permission::create(['guard_name' => 'user', 'name' => 'create press']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit press']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete press']);

        Permission::create(['guard_name' => 'user', 'name' => 'view blog']);
        Permission::create(['guard_name' => 'user', 'name' => 'create blog']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit blog']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete blog']);

        Permission::create(['guard_name' => 'user', 'name' => 'view plan']);
        Permission::create(['guard_name' => 'user', 'name' => 'create plan']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit plan']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete plan']);

        Permission::create(['guard_name' => 'user', 'name' => 'view insight_recipient']);
        Permission::create(['guard_name' => 'user', 'name' => 'create insight_recipient']);
        Permission::create(['guard_name' => 'user', 'name' => 'edit insight_recipient']);
        Permission::create(['guard_name' => 'user', 'name' => 'delete insight_recipient']);

        $role = Role::where('name', 'admin')->first();
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

            'view admin',
            'create admin',
            'edit admin',
            'delete admin',

            'view user',
            'create user',
            'edit user',
            'delete user',

            'view config',
            'create config',
            'edit config',
            'delete config',

            'view insight_recipient',
            'create insight_recipient',
            'edit insight_recipient',
            'delete insight_recipient',

            'view faq',
            'create faq',
            'edit faq',
            'delete faq',

            'view press',
            'create press',
            'edit press',
            'delete press',

            'view blog',
            'create blog',
            'edit blog',
            'delete blog',

            'view group_email_domains',
            'create group_email_domains',
            'edit group_email_domains',
            'delete group_email_domains',

            'view group_email_whitelist',
            'create group_email_whitelist',
            'edit group_email_whitelist',
            'delete group_email_whitelist',

            'view tag',
            'create tag',
            'edit tag',
            'delete tag',

            'view plan',
            'create plan',
            'edit plan',
            'delete plan',
            
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
