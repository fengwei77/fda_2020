<?php

namespace App\Mail;

use App\Mail\NewsletterDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newsletter_content extends Mailable
{
    use Queueable, SerializesModels;

    public $newsletter_detail;

    public function __construct(NewsletterDetail $newsletter_detail)
    {
        //
        $this->newsletter_detail = $newsletter_detail;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.newsletter')
            ->subject($this->newsletter_detail->subject)
            ->with([
                'content' => $this->newsletter_detail->content,
                'username' => $this->newsletter_detail->username,
                'email' => $this->newsletter_detail->email,
                'phone' => $this->newsletter_detail->phone,
            ]);
    }
}
