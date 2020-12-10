<?php

namespace App\Notifications\Shop;

use App\Group;
use App\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionCreated extends Notification
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
        $plan = Plan::where('plan_id', $this->group->subscriptions->first()->stripe_plan)->first();
        $plan_url = "https://portal.marmelade-app.fr/subscription";
        
        return (new MailMessage)
            ->subject(__('[:group_name] Your Subscription Confirmation', ['group_name' => $this->group->name]))
            ->line(__('This email confirms your subscription purchase :'))
            ->line("- [{$plan->name}]({$plan_url}): {$plan->price}€")
            ->line(__("Your subscription will be renewed every month at :price € unless you cancel.", ['price' => $plan->price]))
            ->action(__('Download your invoice'), $this->data['invoice_pdf'])
            ->line(__('Thank you for your purchase!'));
    }
}
