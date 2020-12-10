<?php

namespace App\Notifications\Shop;

use App\Group;
use App\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionCancelled extends Notification
{
    use Queueable;
    private $group;
    private $plan;

    /**
     * Create a new notification instance.
     *
     * @param Group
     * @return void
     */
    public function __construct($group)
    {
        $this->group = $group;
        $this->plan = Plan::where('plan_id', $this->group->subscriptions->first()->stripe_plan)->first();
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

        $plan_url = "https://portal.marmelade-app.fr/subscription";

        return (new MailMessage)
            ->subject(__('[:group_name] Your Subscription has been cancelled', ['group_name' => $this->group->name]))
            ->line(__('This email confirms the cancellation of your subscription [:plan_name](:plan_url).', ['plan_name' => $this->plan->name, 'plan_url' => $plan_url]))
            ->line(__('Your subscription will end on :date, From this date your users will no longer have access to your group.', ['date' => $this->group->subscriptions->first()->ends_at->format('l j F Y')]))
            ->line(__('Thank you for using Marmelade!'));
    }
}
