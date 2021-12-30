<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseReturnRequest extends FormRequest
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
            'product_code' => ['string'],
            'product_name' => ['required', 'string'],
            'return_quantity' => ['required', 'numeric'],
            'return_amount' => ['required', 'numeric'],
            'purchase_order_no' => ['required', 'string'],
            'invoice_no' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            'product_id' => ['required', 'numeric'],
            'is_return_product' => ['boolean'],
        ];
    }
}
