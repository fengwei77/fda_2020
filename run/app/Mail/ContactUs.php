<?php

namespace App\Mail;

use App\ContactDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $contactdetail;

    public function __construct(ContactDetail $contactdetail)
    {
        //
        $this->contactdetail = $contactdetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact_us')
            ->subject($this->contactdetail->cp_name . ' - ' . $this->contactdetail->subject)
            ->with([
                'content' => $this->contactdetail->content,
                'username' => $this->contactdetail->username,
                'email' => $this->contactdetail->email,
                'phone' => $this->contactdetail->phone,
            ]);
    }
}
