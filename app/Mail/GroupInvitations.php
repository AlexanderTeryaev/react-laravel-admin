<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class GroupInvitations extends Mailable //implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitations;
    public $deeplink_base;

    /**
     * Create a new message instance.
     *
     * @param Collection $invitations
     */
    public function __construct(Collection $invitations)
    {
        $this->invitations = $invitations;
        $this->deeplink_base = 'https://join.marmelade-app.fr/private_group?$deeplink_path=app/groups/join/';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo('vamos@marmelade-app.fr')
            ->subject(__('Your Marmelade Groups'))
            ->markdown('emails.group_invitations');
    }
}
