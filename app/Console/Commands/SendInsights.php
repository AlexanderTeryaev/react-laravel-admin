<?php

namespace App\Console\Commands;

use App\Group;
use App\Insight;
use App\InsightRecipient;
use App\Jobs\SendInsightEmail;
use Illuminate\Console\Command;

class SendInsights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insight:send {group_id?} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Insight email to all recipients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function sendInsight($group_id)
    {
        $recipients = InsightRecipient::where('group_id', $group_id);
        if ($recipients->count() > 0)
        {
            $insight = new Insight($group_id);
            foreach ($recipients->get() as $recipient)
                dispatch(new SendInsightEmail($recipient->email, $insight));
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('group_id'))
        {
            $group = Group::find($this->argument('group_id'));
            if (!$group)
            {
                $this->error('Group #'. $this->argument('group_id') .' not found');
                exit();
            }

            if ($this->argument('email'))
            {
                if ($this->confirm('Send the [' . $group->name . '] group report to [' . $this->argument('email') . '] ? [y|N]')) {
                    $insight = new Insight($group->id);
                    dispatch(new SendInsightEmail($this->argument('email') , $insight));
                    $this->info("Done !");
                }
            } else {
                if ($this->confirm('Send the report to all [' . $group->name . '] group recipients ? [y|N]')) {
                    $this->sendInsight($group->id);
                    $this->info("Done !");
                }
            }
        } else {
            $groups = Group::select('id')->get()->pluck('id')->toArray();
            foreach ($groups as $group_id)
            {
                $this->sendInsight($group_id);
                $this->info("Done !");
            }
        }
    }
}
