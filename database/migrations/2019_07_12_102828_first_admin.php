<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Admin;

class FirstAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('admins')->insert([
            'name' => 'Admin',
            'email' => 'elon@marmelade-app.fr',
            'password' => Hash::make('toto42sh'),
            'current_group' => 1,
            'created_at' => now(),
            'status' => true
        ]);

        $user = Admin::find(1);
        $user->assignRole('root');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('admins')->where('email', '=', 'elon@marmelade-app.fr')->where('name', '=', 'Admin')->delete();
    }
}
