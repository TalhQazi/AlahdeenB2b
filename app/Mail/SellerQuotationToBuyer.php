<?php

namespace App\Mail;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerQuotationToBuyer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $buyer;

    public $seller;

    public $quote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $buyer, User $seller, Quotation $quote)
    {
        $this->buyer = $buyer;
        $this->seller = $seller;
        $this->quote = $quote;

        $this->to($buyer->email);
        $this->from(config('mail.from.address'));
        $this->subject(__('Quotation From :seller', ['seller' => $seller->business->company_name ?? $seller->name]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.seller_quotation_to_buyer')
            ->attachFromStorage($this->quote->quotation_path);
    }
}
