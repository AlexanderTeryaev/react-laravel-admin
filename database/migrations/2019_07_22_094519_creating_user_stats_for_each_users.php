<?php

use App\UserStatistics;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatingUserStatsForEachUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = DB::table('users')->get();
        foreach ($users as $user)
        {
            $users_group = DB::table('user_groups')->where('user_id', $user->id)->get();
            if ($users_group)
            {
                foreach ($users_group as $usg)
                {
                    $us = DB::table('user_statistics')->where('user_id', $usg->user_id)->where('group_id', $usg->group_id)->first();
                    if ($us == null)
                        UserStatistics::create([
                            'group_id' => $usg->group_id,
                            'user_id' =>  $usg->user_id,
                            'bad_answers' => 0,
                            'good_answers' => 0,
                            'unlocks' =>  0,
                            'app_rank' => 0,
                            'score' => 0,
                        ]);
                }
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
