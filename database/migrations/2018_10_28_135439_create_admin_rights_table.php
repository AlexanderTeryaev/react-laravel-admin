<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('admin_rights'))
        {
            Schema::create('admin_rights', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('admin_id')->unsigned()->index();
                $table->foreign('admin_id')
                    ->references('id')
                    ->on('admins')
                    ->onDelete('cascade');
                $table->boolean('canCreateAdmin');
                $table->boolean('canEditAdmin');
                $table->boolean('canCreateAuthor');
                $table->boolean('canEditAuthor');
                $table->boolean('canCreateCategory');
                $table->boolean('canEditCategory');
                $table->boolean('canCreateFeatured');
                $table->boolean('canEditFeatured');
                $table->boolean('canCreateGroup');
                $table->boolean('canEditGroup');
                $table->boolean('canCreateQuestion');
                $table->boolean('canEditQuestion');
                $table->boolean('canCreateQuizz');
                $table->boolean('canEditQuizz');
                $table->boolean('canCreateTag');
                $table->boolean('canEditTag');
                $table->boolean('canCreateUser');
                $table->boolean('canEditUser');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_rights');
    }
}
