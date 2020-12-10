<?php

namespace App\Mail;

use App\Group;
use App\Insight;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsightEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $insight;

    /**
     * Create a new message instance.
     *
     * @param  \App\Insight  $insight
     * @return void
     */
    public function __construct(Insight $insight)
    {
        $this->insight = $insight;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $group = Group::select('name')->where('id', $this->insight->group_id)->first();

        return $this->from('insights@marmelade-app.fr', 'Marmelade App')
            ->subject('['. $group->name . '] Statistiques de la semaine')
            ->view('emails.insight.report')
            ->text('emails.insight.report_plain')
            ->with('insight', $this->insight);
    }
}
