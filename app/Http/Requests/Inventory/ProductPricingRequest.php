<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductPricingRequest extends FormRequest
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
        $rules = [
            'total_units' => ['required', 'numeric'],
            'price_per_unit' => ['required', 'numeric'],
            'avg_cost_per_unit' => ['required', 'numeric'],
            'sales_tax_percentage' => ['nullable', 'numeric'],
            'allow_below_cost_sale' => ['nullable', Rule::in(['on'])],
            'allow_price_change' => ['nullable', Rule::in(['on'])],
            'discount_percentage' => ['nullable', 'numeric'],
            'promotional_product_id' => ['nullable'],
            'promotional_discount_percentage' => ['nullable', 'numeric'],
            'promotion_description' => ['nullable', 'string']
        ];

        if($this->isMethod('put')) {

            $rules['product_id'] = [
                'nullable',
                'integer',
                Rule::unique('App\Models\Inventory\ProductPricing', 'product_id')->ignore($this->product_id, 'product_id'),
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ];

            $rules['promotional_product_id'] = [
                'nullable',
                'integer',
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ];



        } else {
            $rules['product_id'] = [
                'required',
                'integer',
                Rule::unique('App\Models\Inventory\ProductPricing', 'product_id'),
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ];

            $rules['promotional_product_id'] = [
                'nullable',
                'integer',
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        if( $this->isMethod('post') && !empty($this->product_id) ) {
            $messages['product_id.unique'] = __('Product Pricing against the selected product already exists in the inventory');
        }

        return $messages;
    }
}
