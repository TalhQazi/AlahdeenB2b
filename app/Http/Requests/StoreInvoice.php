<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreInvoice extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => ['string'],
            'invoice_date' => ['required', 'date', 'date_format:Y-m-d'],
            'payment_due_date' => ['required', 'date', 'date_format:Y-m-d'],
            'delivery_date' => ['required', 'date', 'date_format:Y-m-d H:i'],
            'client_id' => [
              'required',
              Rule::exists('clients')->where(function ($query) {
                return $query->where('user_id', Auth::user()->id);
              }),
            ],
            'terms_and_conditions' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string'],
            'freight_charges' => ['nullable', 'numeric'],
            'item' => ['required', 'array'],
            'item.*' => ['required', 'array'],
            'item.*.name' => ['required', 'string'],
            'item.*.id' => ['nullable', 'numeric'],
            'item.*.promotion_id' => ['nullable', 'numeric'],
            'item.*.code' => ['required', 'string'],
            'item.*.qty' => ['required', 'numeric'],
            'item.*.unit' => ['required', 'string'],
            'item.*.rate' => ['required', 'numeric'],
            'item.*.gst' => ['required', 'numeric'],
            'purchase_order' => ['file', 'nullable', 'mimes:jpeg,jpg,png,pdf', 'max:500'],
            'delivery_challan' => ['file', 'nullable', 'mimes:jpeg,jpg,png,pdf', 'max:500'],
            'shipment_receipt' => ['file', 'nullable', 'mimes:jpeg,jpg,png,pdf', 'max:500'],
            'tax_certificate' => ['file', 'nullable', 'mimes:jpeg,jpg,png,pdf', 'max:500'],
            'digital_signature' => ['image', 'nullable', 'mimes:jpeg,jpg,png', 'max:500'],

        ];
    }
}
