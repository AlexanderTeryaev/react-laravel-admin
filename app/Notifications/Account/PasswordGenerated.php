<?php

namespace App\Notifications\Account;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordGenerated extends Notification
{
    use Queueable;
    private $email;
    private $password;

    /**
     * Create a new notification instance.
     *
     * @param string
     * @param string
     * @return void
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
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
        $url = "https://portal.marmelade-app.fr/login";
        return (new MailMessage)
            ->subject(__('Your login details'))
            ->line(__('messages.notifications.account.password_generated'))
            ->line(__('**Username:** :email', ['email' => $this->email]))
            ->line(__('**Temporary password:** :password', ['password' => $this->password]))
            ->action(__('Go to the portal'), $url)
            ->line(__('Thank you for using Marmelade!'));
    }
}
