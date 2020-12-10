<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use App\User;

class UserPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::create(['guard_name' => 'user', 'name' => 'admin']);
        $admins = [
          'youssef@marmelade-app.fr', 'mohammed@marmelade-app.fr', 'julie@marmelade-app.fr',
            'clement@marmelade-app.fr', 'marc@marmelade-app.fr', 'benjamin@marmelade-app.fr'
        ];

        foreach ($admins as $admin){
            $user = User::where('email', $admin)->first();
            if ($user){
                $user->manageable_groups()->sync([]);
                $user->assignRole('admin');
            }
        }
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
