<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUs as MailContactUs;


class ContactUsController extends Controller
{
    use ApiResponser;

    public function store(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string',
            'subject' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_full' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'message' => 'required|min:50',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $contact = DB::transaction(function () use ($request) {
            return ContactUs::create([
                'inquiry_type' => $request->purpose,
                'subject' => $request->subject,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone_full,
                'message' => $request->message,
            ]);
        });

        if ($contact) {
            Mail::queue(new MailContactUs($contact));

            return $this->success([], __('Thanks for contacting us, we have received your email and someone from support will contact you soon'));
        } else {
            return $this->error(__('Unable to process this request, Please try again later'), 400);
        }
    }
}
