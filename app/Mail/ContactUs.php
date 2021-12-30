<?php

namespace App\Mail;

use App\Models\ContactUs as ModelsContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ModelsContactUs $contact)
    {
        $this->contact = $contact;
        $this->to(config('mail.contact_us.address'), config('mail.contact_us.name'));
        $this->from($contact->email);
        $this->subject($contact->subject.' - '.$contact->inquiry_type);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact_us');
    }
}
