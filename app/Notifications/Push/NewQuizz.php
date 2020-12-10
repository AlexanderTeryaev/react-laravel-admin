<?php

namespace App\Notifications\Push;

use App\Quizz;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewQuizz extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Quizz
     */
    private $quizz;

    /**
     * Create a new notification instance.
     *
     * @param Quizz $quizz
     */
    public function __construct(Quizz $quizz)
    {
        $this->quizz = $quizz;
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
            ->setSubject(__('[:group_name] A new quizz is available', ['group_name' => $this->quizz->group->name]))
            ->setBody(__(':quizz_name has been added, subscribe to test yourself.', ['quizz_name' => $this->quizz->name]))
            ->setData('screen', 'quiz')
            ->setData('target_id', $this->quizz->id)
            ->setData('group', $this->quizz->group->only('id', 'name', 'logo_url'))
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
            'subject' => __('[:group_name] A new quizz is available', ['group_name' => $this->quizz->group->name]),
            'body' => __(':quizz_name has been added, subscribe to test yourself.', ['quizz_name' => $this->quizz->name]),
            'screen' => 'quiz',
            'target_id' => $this->quizz->id,
            'group' => $this->quizz->group->only('id', 'name', 'logo_url'),
            'should_switch' => true
        ];
    }
}
