<?php

namespace App\Notifications\Slack;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewGroupCreated extends Notification
{
    use Queueable;

    private $user;
    private $group;

    /**
     * Create a new notification instance.
     * @param
     * @return void
     */
    public function __construct($user, $group)
    {
        $this->user = $user;
        $this->group = $group;
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
            ->content('New group created!')
            ->attachment(function ($attachment) {
                $attachment->title($this->group->name. ' has just been created ')
                    ->fields([
                        'email' => $this->user->email,
                        'name' => $this->user->first_name .' '. $this->user->last_name,
                        'phone_number' => $this->user->phone_number,
                        'group_id' => $this->group->id
                    ]);
            });
    }
}
