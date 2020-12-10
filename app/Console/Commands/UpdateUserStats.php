<?php

namespace App\Console\Commands;

use App\UserStatistics;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUserStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateuserstats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update/Create User stats for users in group who didn t have yet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users_group = DB::table('user_groups')->get();
        $created = 0;
        $updated = 0;
        foreach ($users_group as $usg)
        {
            $good_answers = DB::table('user_answers')->where('group_id', $usg->group_id)
                                                            ->where('user_id', $usg->user_id)
                                                            ->where('result', true)
                                                            ->count();
            $bad_answers = DB::table('user_answers')->where('group_id', $usg->group_id)
                                                            ->where('user_id', $usg->user_id)
                                                            ->where('result', false)
                                                            ->count();
            $score = ($good_answers * 2) - $bad_answers;
            $unlocks =  DB::table('user_answers')->where('group_id', $usg->group_id)
                                                        ->where('user_id', $usg->user_id)
                                                        ->where('is_enduro', false)
                                                        ->count();

            $us = DB::table('user_statistics')->where('user_id', $usg->user_id)->where('group_id', $usg->group_id);
            if ($us->count() == 0) {
                UserStatistics::create([
                    'group_id' => $usg->group_id,
                    'user_id' => $usg->user_id,
                    'bad_answers' => $bad_answers,
                    'good_answers' => $good_answers,
                    'unlocks' => $unlocks,
                    'app_rank' => 0,
                    'score' => $score,
                ]);
                $created++;
            } else {
                if ($us->count() > 1){
                    DB::table('user_statistics')
                        ->where('user_id', $usg->user_id)
                        ->where('group_id', $usg->group_id)
                        ->delete();
                    UserStatistics::create([
                        'group_id' => $usg->group_id,
                        'user_id' => $usg->user_id,
                        'bad_answers' => $bad_answers,
                        'good_answers' => $good_answers,
                        'unlocks' => $unlocks,
                        'app_rank' => 0,
                        'score' => $score,
                    ]);
                } else {
                    DB::table('user_statistics')
                        ->where('user_id', $usg->user_id)
                        ->where('group_id', $usg->group_id)
                        ->update([
                            'bad_answers' => $bad_answers,
                            'good_answers' => $good_answers,
                            'unlocks' => $unlocks,
                            'score' => $score,
                        ]);
                }
                $updated++;
            }
        }
        $this->info('Done ! '. $created . ' user_stats created and '. $updated .' updated !');
    }
}
