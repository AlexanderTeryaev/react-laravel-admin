<?php

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CleanUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            DB::statement('ALTER TABLE users DROP `name`;');
            DB::statement('ALTER TABLE users DROP email;');
            DB::statement('ALTER TABLE users DROP mood;');
            DB::statement('ALTER TABLE users DROP age;');
            DB::statement('ALTER TABLE users DROP country;');
            DB::statement('ALTER TABLE users DROP state;');

            $table->string('username', 15)->after('device_id');
            $table->string('email')->nullable()->after('username');
            $table->string('avatar_url')->nullable()->after('email');
            $table->string('bio', 50)->nullable()->after('avatar_url');

            DB::statement('ALTER TABLE users CHANGE one_signal_id old_one_signal_id VARCHAR(191)');
            DB::statement('ALTER TABLE users CHANGE reputation old_reputation INT(11)');
            DB::statement('ALTER TABLE users CHANGE can_submit_report old_can_submit_report tinyint(1)');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('one_signal_id')->nullable()->after('bio');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('reputation')->after('one_signal_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_submit_report')->default(true)->after('reputation');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->ipAddress('last_ip')->after('curr_app_version');
        });
        DB::table('users')->update([
            'one_signal_id' => DB::raw('old_one_signal_id'),
            'reputation' => DB::raw('old_reputation'),
            'can_submit_report' => true,
            'last_ip' => DB::raw('ip')
        ]);
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['old_one_signal_id', 'old_reputation', 'old_can_submit_report', 'deleted_at', 'ip']);
        });

        foreach (User::all() as $user) {
            $username = Str::random(mt_rand(4, 10));
            $nb = 1;
            while (true){
                if (User::where('username', $username)->exists())
                    $username = $username . $nb++;
                else
                    break;
            }
            $user->update(['username' => $username]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('mood')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->dropColumn(['username', 'avatar_url', 'bio']);
        });
    }
}
