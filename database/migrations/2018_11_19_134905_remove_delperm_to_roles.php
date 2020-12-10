<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RemoveDelpermToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $root = Role::findByName('root');
        $root->revokePermissionTo('delete group');
        $root->revokePermissionTo('delete quizz');
        $root->revokePermissionTo('delete question');
        $root->revokePermissionTo('delete author');
        $root->revokePermissionTo('delete category');
        $root->revokePermissionTo('delete featured');
        $root->revokePermissionTo('delete tag');
        $root->revokePermissionTo('delete admin');
        $root->revokePermissionTo('delete user');

        $it = Role::findByName('it');
        $it->revokePermissionTo('delete group');
        $it->revokePermissionTo('delete quizz');
        $it->revokePermissionTo('delete question');
        $it->revokePermissionTo('delete author');
        $it->revokePermissionTo('delete category');
        $it->revokePermissionTo('delete featured');
        $it->revokePermissionTo('delete tag');
        $it->revokePermissionTo('delete admin');
        $it->revokePermissionTo('delete user');

        $management = Role::findByName('management');
        $management->revokePermissionTo('delete group');
        $management->revokePermissionTo('delete quizz');
        $management->revokePermissionTo('delete question');
        $management->revokePermissionTo('delete author');
        $management->revokePermissionTo('delete category');
        $management->revokePermissionTo('delete featured');
        $management->revokePermissionTo('delete tag');
        $management->revokePermissionTo('delete admin');
        $management->revokePermissionTo('delete user');

        $content = Role::findByName('content-manager');
        $content->revokePermissionTo('delete group');
        $content->revokePermissionTo('delete quizz');
        $content->revokePermissionTo('delete question');
        $content->revokePermissionTo('delete author');
        $content->revokePermissionTo('delete category');
        $content->revokePermissionTo('delete featured');
        $content->revokePermissionTo('delete tag');
        $content->revokePermissionTo('delete admin');
        $content->revokePermissionTo('delete user');

        $noob = Role::findByName('noob');
        $noob->revokePermissionTo('delete group');
        $noob->revokePermissionTo('delete quizz');
        $noob->revokePermissionTo('delete question');
        $noob->revokePermissionTo('delete author');
        $noob->revokePermissionTo('delete category');
        $noob->revokePermissionTo('delete featured');
        $noob->revokePermissionTo('delete tag');
        $noob->revokePermissionTo('delete admin');
        $noob->revokePermissionTo('delete user');
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
