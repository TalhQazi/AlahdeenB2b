<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
          'po_number' => ['required', 'string'],
          'po_date' => ['required', 'date', 'date_format:Y-m-d'],
          'po_delivery_date' => ['required', 'date', 'date_format:Y-m-d H:i'],
          'attachment' => ['file', 'nullable', 'mimes:jpeg,jpg,png,pdf', 'max:500'],
          'order_description' => ['required', 'string'],
          'payment_details' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
      return [
        'order_description.required' => __('Purchase Order details can not be left empty'),
      ];
    }
}
