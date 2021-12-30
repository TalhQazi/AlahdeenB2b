<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
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
                                    return $fail(__('Product can only be added at sub category level 2 or below'));
                                }
                            }

                        }
                    }
            ],
            'price' => ['required', 'numeric', 'min:1'],
            'product_details_key' => ['array'],
            'product_details_key.*' => ['required', 'string', 'max:255'],
            'product_details_value' => ['array'],
            'product_details_value.*' => ['required', 'string', 'max:255'],
            'product_images' => ['array'],
            'product_images.*' => ['file', 'nullable'],
            'product_video_link' => ['string' ,'nullable', 'max:255'],
            'product_document' => ['file', 'nullable'],
            'is_featured' => ['nullable', Rule::in(['on'])],
        ];
    }

    public function attributes()
    {
        return [
            'product_details_key.*' => 'product details key',
            'product_details_value.*' => 'product details value'
        ];
    }
}
