<?php

namespace App\Notifications\Account;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPassword extends Notification
{
    use Queueable;
    private $token;

    /**
     * Create a new notification instance.
     *
     * @param string
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $link = "https://portal.marmelade-app.fr/password/reset/{$this->token}";
        return (new MailMessage)
            ->subject(__('Reset your password'))
            ->greeting(__('Reset your password'))
            ->line(__('We received a request to reset your password. To reset your password, click the link below:'))
            ->action(__('Reset'), $link)
            ->line(__("If you didn't request a reset, don't worry. You can safely ignore this message. email."));
    }
}
