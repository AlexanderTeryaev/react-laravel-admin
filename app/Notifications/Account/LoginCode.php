<?php

namespace App\Notifications\Account;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginCode extends Notification
{
    use Queueable;

    private $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Your Temporary Marmelade Login Code is :code', ['code' => $this->code]))
            ->greeting(__('Let’s get you logged in'))
            ->line(__('We use this easy login code so you don’t have to remember or type in yet another long password.'))
            ->line(__('Your login code is:'))
            ->line('# ' . $this->code)
            ->line(__('Please note this code is only valid for 15 minutes.'))
            ->line(__("This email address has been added to a Marmelade account. If you received it by mistake or weren't expecting it, please disregard this email."));
    }

}
