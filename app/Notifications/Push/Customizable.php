<?php

namespace App\Notifications\Push;

use App\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class Customizable extends Notification implements ShouldQueue
{
    use Queueable;

    private $subject;
    private $body;
    private $screen;
    private $target_id;
    private $group;
    private $should_switch;

    /**
     * Create a new notification instance.
     *
     * @param string $subject
     * @param string $body
     * @param string $screen
     * @param int $target_id
     * @param Group $group
     * @param bool $should_switch
     */
    public function __construct(string $subject, string $body, string $screen, int $target_id, Group $group, bool $should_switch)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->screen = $screen;
        $this->target_id = $target_id;
        $this->group = $group;
        $this->should_switch = $should_switch;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->one_signal_id)
            return [OneSignalChannel::class, 'database'];
        return ['database'];
    }

    /**
     * Send push notification via onesignal
     *
     * @param  mixed  $notifiable
     * @return OneSignalMessage
     */
    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject($this->subject)
            ->setBody($this->body)
            ->setData('screen', $this->screen)
            ->setData('target_id', $this->target_id)
            ->setData('group', $this->group->only('id', 'name', 'logo_url'))
            ->setData('should_switch', $this->should_switch);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'subject' => $this->subject,
            'body' => $this->body,
            'screen' => $this->screen,
            'target_id' => $this->target_id,
            'group' => $this->group->only('id', 'name', 'logo_url'),
            'should_switch' => $this->should_switch
        ];
    }
}
