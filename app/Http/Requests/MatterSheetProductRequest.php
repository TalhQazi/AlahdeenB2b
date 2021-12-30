<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class MatterSheetProductRequest extends FormRequest
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
        $rules =  [
            'logo' => ['file'],
            'title' => ['required', 'string', 'min:3'],
            'price' => ['required', 'numeric'],
            'category_id' => ['required'],
            'sub_category_id' => ['required'],
        ];

        return $rules;

    }

    public function messages()
    {
        $messages = [
            'logo.file' => 'Logo is required',
            'title.required' => 'Enter title',
            'price.required' => 'Price should be numeric',
            'price.numeric' => 'Price is required',
            'category_id.required' => 'Category is required',
            'sub_category_id.required' => 'Sub Category is required',
        ];

        return $messages;
    }
}
