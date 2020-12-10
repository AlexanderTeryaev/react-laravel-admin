<?php

namespace App\Notifications\Shop;

use App\Group;
use App\ShopTraining;
use FontLib\Table\Type\name;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TrainingPurchased extends Notification
{
    use Queueable;
    private $group;
    private $training;

    /**
     * Create a new notification instance.
     *
     * @param Group
     * @param ShopTraining
     * @return void
     */
    public function __construct($group, $training)
    {
        $this->group = $group;
        $this->training = $training;
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
        $training_url = "https://portal.marmelade-app.fr/catalog/{$this->training->id}";
        return (new MailMessage)
            ->subject(__('[:group_name] Your training has been added to the group', ['group_name' => $this->group->name]))
            ->line(__('You’ve purchased the following training:'))
            ->line("- [{$this->training->name}]({$training_url}): {$this->training->price} coings")
            ->line(__('Thank you for your purchase!'));
    }
}
