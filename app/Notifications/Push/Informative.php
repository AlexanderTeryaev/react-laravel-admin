<?php

namespace App\Notifications\Push;

use App\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class Informative extends Notification implements ShouldQueue
{
    use Queueable;

    private $body;
    private $group;

    /**
     * Create a new notification instance.
     *
     * @param Group $group
     * @param string $body
     */
    public function __construct(Group $group, string $body)
    {
        $this->body = $body;
        $this->group = $group;
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
            ->setSubject(__('[:group_name] New message:', ['group_name' => $this->group->name]))
            ->setBody(Str::limit($this->body, 50)) // Todo: Remove markdown
            ->setData('screen', 'notification_detail')
            ->setData('group', $this->group->only('id', 'name', 'logo_url'))
            ->setData('should_switch', true);
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
            'subject' => __('[:group_name] New message:', ['group_name' => $this->group->name]),
            'body' => $this->body,
            'screen' => 'notification_detail',
            'group' => $this->group->only('id', 'name', 'logo_url'),
            'should_switch' => true
        ];
    }
}
