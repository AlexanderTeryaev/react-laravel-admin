<?php

namespace App\Notifications\Account;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $token = md5($notifiable->current_group_portal . '-' . $notifiable->id . '-' . $notifiable->email);
        $url = "https://portal.marmelade-app.fr/email_verification/{$notifiable->email}/{$token}";

        return (new MailMessage)
            ->subject(__('Activate your account'))
            ->greeting(__('Please confirm your email'))
            ->line(__('Thanks for signing up. We’re thrilled to have you on board! Here is your email verification link :'))
            ->action(__('Activate your account'), $url)
            ->line(__("This email address has been added to a Marmelade account. If you received it by mistake or weren't expecting it, please disregard this email."));
    }
}
