<?php

namespace App\Mail;

use App\Models\QuotationRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSupplier extends Mailable
{
    use Queueable, SerializesModels;

    public $buyer;

    public $seller;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $buyer, User $seller)
    {
        $this->buyer = $buyer;
        $this->seller = $seller;

        $this->to($seller->email);
        $this->subject(__(':buyer_name sent a quotation request', ['buyer_name' => $buyer->name]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact_supplier');
    }
}
