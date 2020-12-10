<?php

namespace App\Notifications;

use App\Group;
use App\UserAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class UserNotification extends Notification
{
    use Queueable;
    private $data;
    private $group;

    /**
     * Create a new notification instance.
     *
     * @return void
     **/
    public function __construct($param)
    {
        $this->data = $param;
        $this->group = Group::find(1);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [OneSignalChannel::class, 'database'];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject($this->data["subject"])
            ->setBody($this->data["body"])
            ->setData('screen', $this->data["screen"])
            ->setData('target_id', $this->data["id"])
            ->setData('group', $this->quizz->group->only('id', 'name', 'logo_url'))
            ->setData('should_switch', true);
    }

    public function toDatabase($notifiable)
    {
        return [
            'subject' => $this->data["subject"],
            'body' => $this->data["body"],
            'screen' => $this->data["screen"],
            'target_id' => $this->data["id"],
            'group' => $this->quizz->group->only('id', 'name', 'logo_url'),
            'should_switch' => true
        ];
    }
}
