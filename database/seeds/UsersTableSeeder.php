<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $user = factory(App\User::class)->states('verified')->create([
            'email' => 'elon@marmelade-app.fr',
            'current_group_portal' => 1
        ]);
        $user->addInGroup(1, 'default');
        $user->assignRole('admin');

        // Group Manager
        $user = factory(App\User::class)->states('verified')->create([
            'email' => 'john@marmelade-app.fr',
        ]);
        $user->update(['current_group_portal' => 2]);
        $user->addInGroupManagers(2);

        // Create users
        factory(App\User::class, mt_rand(50, 100))->create()->each(function ($user){
            $user->addInGroup(1, 'default');
            for ($i = 0; $i < mt_rand(0, 10); $i++)
            {
                $user_groups = $user->groups()->pluck('groups.id');
                $group_id = \App\Group::whereNotIn('id', $user_groups)->get()->random()->id;
                if (isset($group_id))
                    $user->addInGroup($group_id, 'master_key');
            }
        });

    }
}
