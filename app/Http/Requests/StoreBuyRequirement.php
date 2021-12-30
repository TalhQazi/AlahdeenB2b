<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBuyRequirement extends FormRequest
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
            'product_id' => ["nullable"],
            'product' => [Rule::requiredIf($this->product_id == null), "nullable", "string"],
            'requirement_details' => ["required", "string"],
            'image_path' => ['string', 'nullable'],
            'quantity' => ["required", "numeric"],
            'unit' => ["required", "string", Rule::in(config('quantity_unit'))],
            'budget' => ["nullable", "numeric"],
            'requirement_urgency' => ["nullable", "string", Rule::in(['immediate', 'after one month'])],
            'requirement_frequency' => ["nullable", "string", Rule::in(['one time', 'regular'])],
            'supplier_location' => ["nullable", "string", Rule::in('local','any where in pakistan')],
            'terms_and_conditions' => ["required", "accepted"],
        ];
    }

}
