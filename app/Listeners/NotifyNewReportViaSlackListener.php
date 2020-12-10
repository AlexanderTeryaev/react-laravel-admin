<?php

namespace App\Listeners;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyNewReportViaSlackListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Laravel slack lib don't allow us to specify webhook
        (new SlackMessage)
            ->content('New user report !')
            ->attachment(function ($attachment) use($event){
                $attachment->title('Report #' . $event->report->id, route('reports.show', [$event->report->id]))
                    ->fields([
                        'Question' => $event->report->question->question,
                        'Reason' =>  $event->report->report,
                        'User Id' => $event->report->user_id,
                        'Group' => $event->report->group->name,
                    ]);
            });
    }
}
