<?php

namespace App\Notifications\Shop;

use App\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class SubscriptionRenewed extends Notification
{
    use Queueable;
    private $group;
    private $data;

    /**
     * Create a new notification instance.
     *
     * @param Group
     * @param array Data received in webhook
     * @return void
     */
    public function __construct($group, $data)
    {
        $this->group = $group;
        $this->data = $data;
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
        $url = "https://portal.marmelade-app.fr/subscription";
        $invoice_url = $this->data['invoice_pdf'] ?? $url;

        if (!isset($this->data['invoice_pdf']))
            Log::error("Unable to retrieve invoice_url from stripe webhook");

        return (new MailMessage)
            ->subject(__('[:group_name] Your plan has renewed', ['group_name' => $this->group->name]))
            ->line(__('This e-mail confirms the renewal of your subscription, you can manage your subscription at any time on [this page](:url).', compact('url')))
            ->action(__('Download your invoice'), $invoice_url)
            ->line(__('Thank you for using Marmelade!'));
    }
}
