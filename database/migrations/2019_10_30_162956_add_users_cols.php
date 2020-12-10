<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUsersCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        DB::statement("ALTER TABLE users MODIFY COLUMN curr_os ENUM('n/a', 'ios', 'android', 'portal')");
        Schema::table('groups', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('last_ip', 39)->nullable()->change();
            $table->string('first_name')->nullable()->after('device_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->timestamp('email_verified_at')->nullable()->after('last_ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN curr_os ENUM('n/a', 'ios', 'android')");
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('email_verified_at');
        });
    }
}
