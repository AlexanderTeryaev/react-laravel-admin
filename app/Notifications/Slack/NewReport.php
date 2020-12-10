<?php

namespace App\Notifications\Slack;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;


class NewReport extends Notification
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable){
        return (new SlackMessage)
            ->content('New user report !')
            ->attachment(function ($attachment) {
                $attachment->title('Report #' . $this->report->id, route('reports.show', [$this->report->id]))
                    ->fields([
                        'Question' => $this->report->question->question,
                        'Reason' => $this->report->report,
                        'User Id' => $this->report->user_id,
                        'Group' => $this->report->group->name,
                    ]);
            });
    }
}
