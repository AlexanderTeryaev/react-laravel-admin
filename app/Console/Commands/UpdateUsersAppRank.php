<?php

namespace App\Console\Commands;

use App\UserStatistics;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUsersAppRank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateusersapprank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user rank in app';

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
        $groups = DB::table('groups')->select('id')->get();
        foreach ($groups as $group) {
            $user_stats = DB::table('user_statistics')->select()
                ->where('group_id', $group->id)
                ->orderBy('score', 'DESC')->get();
            $index = 1;
            foreach ($user_stats as $stat) {
                $stats = UserStatistics::select()
                    ->where('user_id', $stat->user_id)
                    ->where('group_id', $stat->group_id)
                    ->first();
                $stats->update(['app_rank' => $index]);
                ++$index;
            }
        }
    }
}
