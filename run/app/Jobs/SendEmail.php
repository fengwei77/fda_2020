<?php

namespace App\Jobs;

use App\Mail\Newsletter_content;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @param Newsletter_content $email
     */
    public function __construct(Newsletter_content $email)
    {
        $this->email = $email;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
