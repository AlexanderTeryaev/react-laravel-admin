<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('blog')) {
            Schema::create('blog', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->text('content');
                $table->string('author');
                $table->dateTime('posted_at');
                $table->json('tags');
                $table->softDeletes();
                $table->timestamps();
            });
        }
        Permission::create(['name' => 'view blog']);
        Permission::create(['name' => 'create blog']);
        Permission::create(['name' => 'edit blog']);
        Permission::create(['name' => 'delete blog']);

        $role = Role::findByName('root');
        $role->givePermissionTo([
            'view blog',
            'create blog',
            'edit blog',
            'delete blog',
        ]);

        $role = Role::findByName('it');
        $role->givePermissionTo([
            'view blog',
            'create blog',
            'edit blog',
            'delete blog',
        ]);

        $role = Role::findByName('management');
        $role->givePermissionTo([
            'view blog',
            'create blog',
            'edit blog',
            'delete blog',
        ]);

        $role = Role::findByName('content-manager');
        $role->givePermissionTo([
            'view blog',
            'create blog',
            'edit blog',
            'delete blog',
        ]);

        $role = Role::findByName('noob');
        $role->givePermissionTo([
            'view blog',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog');
        $role = Role::findByName('root');
        $role->revokePermissionTo(['view blog',
            'create blog',
            'edit blog',
            'delete blog',]);

        $role = Role::findByName('it');
        $role->revokePermissionTo(['view blog',
            'create blog',
            'edit blog',
            'delete blog',]);

        $role = Role::findByName('management');
        $role->revokePermissionTo(['view blog',
            'create blog',
            'edit blog',
            'delete blog',]);

        $role = Role::findByName('content-manager');
        $role->revokePermissionTo(['view blog',
            'create blog',
            'edit blog',
            'delete blog',]);
        $role = Role::findByName('noob');
        $role->revokePermissionTo(['view blog']);
    }
}
