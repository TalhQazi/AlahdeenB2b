<?php

namespace App\Http\Requests\Inventory;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProductDefinition extends FormRequest
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
            'product_image' => ['required'],
            'product_code' => ['required'],
            'product_name' => ['required'],
            'category' => ['required', 'array'],
            'category.*' => ['required',
                function($key, $value, $fail) {
                    $index = (String) str_replace('category.', '', $key);
                    if( $index == count($this->category)) {
                        //Checking if the sub category actually falls at that the specified level
                        if( Category::where('level', $index)->where('id', $value)->count() < 1 ) {
                            return $fail(__('Invalid category provided'));
                        } else {
                            //Checking for user to add product sub category 3 or below
                            if( Category::where('level', '>=', 2)->where('id', $value)->count() < 1 ) {
                                return $fail(__('Product can only be added at sub category level 3 or below'));
                            }
                        }

                    }
                }
            ],
            'product_group' => ['nullable', 'string'],
            'purchase_unit' => ['required', Rule::in(config('quantity_unit'))],
            'conversion_factor' => ['required', 'min:1'],
            'brand_name' => ['nullable', 'string'],
            'product_gender' => ['nullable', 'string'],
            'value_addition' => ['nullable', 'string'],
            'life_type' => ['nullable', 'string'],
            'tax_code' => ['nullable', 'string'],
            'supplier' => ['nullable', 'string'],
            'accquire_type' => ['nullable', 'string'],
            'additional_attributes' => ['nullable', 'string'],
            'technical_details' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'purchase_type' => ['nullable', 'string'],
            'purchase_production_interval' => ['required', 'integer'],
            'purchase_production_unit' => ['required', 'string', Rule::in(['days', 'months', 'years'])]
        ];

        if($this->isMethod('put')) {

            $rules['product_id'] = [
                'nullable',
                'integer',
                Rule::unique('App\Models\Inventory\ProductDefinition', 'product_id')->ignore($this->product_id, 'product_id'),
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ];

        } else {
            $rules['product_id'] = [
                'nullable',
                'integer',
                Rule::unique('App\Models\Inventory\ProductDefinition', 'product_id'),
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
            $messages['product_id.unique'] = __('Product Definition against the selected product already exists in the inventory');
        }

        return $messages;
    }
}
