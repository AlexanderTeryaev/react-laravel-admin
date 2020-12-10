<?php

namespace App\Notifications\Account;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \App\GroupInvitation as Invitation;

class GroupInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    private $invitation;
    private $manager;
    private $manager_name;
    private $deeplink_url;

    /**
     * Create a new notification instance.
     *
     * @param \App\GroupInvitation
     * @param \App\User
     * @return void
     */
    public function __construct(Invitation $invitation, User $manager)
    {
        $this->invitation = $invitation;
        $this->manager = $manager;

        if ($manager->first_name)
            $this->manager_name = $manager->first_name . ' ' . $manager->last_name;
        else
            $this->manager_name = $manager->username;

        $this->deeplink_url = 'https://join.marmelade-app.fr/private_group?$deeplink_path=app/groups/join/'. $invitation->group->id .'/'. $invitation->token;

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
        return (new MailMessage)
            ->replyTo('vamos@marmelade-app.fr')
            ->subject(__(':name has invited you to join :group_name group', [
                'name' => $this->manager_name,
                'group_name' => $this->invitation->group->name
            ]))->greeting(__('Join :group_name on Marmelade', [
                'group_name' => $this->invitation->group->name
            ]))->line(__('**:name** (*:email*) has invited you to join the Marmelade group **:group_name**. Join now to start learning!', [
                'name' => $this->manager_name,
                'email' => $this->manager->email,
                'group_name' => $this->invitation->group->name
            ]))->line(__('⚠ You must click on the button from your smartphone ⚠'))
            ->action(__('Join Now'), $this->deeplink_url)
            ->line(__('If you have any problems, you can contact us [vamos@marmelade-app.fr](mailto:vamos@marmelade-app.fr).'))
            ->line(__('Thank you for using Marmelade!'));
    }
}
