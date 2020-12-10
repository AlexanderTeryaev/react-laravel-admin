<?php

namespace App\Jobs;

use App\Mail\InsightEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendInsightEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 120;

    private $insight;
    private $recipient_email;

    /**
     * Create a new job instance.
     *
     * @param $recipient_email
     * @param \App\Insight $insight
     * @return void
     */
    public function __construct($recipient_email, $insight)
    {
        $this->insight = $insight;
        $this->recipient_email = $recipient_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->recipient_email)->send(new InsightEmail($this->insight));
    }


}
