<?php

namespace App\Notifications\Slack;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class Contact extends Notification
{
    use Queueable;

    protected $contact_form;
    protected $platform;
    /**
     * Create a new notification instance.
     * @param $contact_form
     * @return void
     */
    public function __construct($contact_form, $platform)
    {
        $this->contact_form = $contact_form;
        $this->platform = $platform;
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
            ->content('New contact request !')
            ->attachment(function ($attachment) {
                $attachment->title($this->contact_form->name. ' just made us a contact request')
                    ->fields([
                        'email' => $this->contact_form->email,
                        'subject' => $this->contact_form->subject,
                        'message' => $this->contact_form->message,
                        'platform' => $this->platform
                    ]);
            });
    }
}
