<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouse extends FormRequest
{
    private $settings;


    public function __construct()
    {

        $this->settings =  config('images_configuration.warehouse', config('images_configuration.default'));
    }

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
            'property_type_id' => ['required', 'numeric'],
            'area' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'city_id' => ['required', 'numeric'],
            'locality_id' => ['required', 'numeric'],
            'lat' => ['numeric'],
            'lng' => ['numeric'],
            'can_be_shared' => ['nullable', Rule::in(['on'])],
            'warehouse_images' => ['array', 'nullable'],
            'warehouse_images.*' => ['file', 'nullable'],
            'feature_name' => ['array', 'nullable'],
            'warehouse_image_id' => ['nullable', 'array'],
            'warehouse_image_id.*' => ['numeric'],
            'main_image' => ['nullable', 'numeric']
        ];
    }

    public function messages()
    {
        $messages = [];
        if( !empty($this->property_type_id) ) {
            $messages['property_type_id.numeric'] = __('Please select valid parent warehouse type');
        }

        if( empty($this->city_id) ) {
            $messages['property_type_id.required'] = __('Please enter city');
        }

        if( empty($this->locality_id) ) {
            $messages['locality_id.required'] = __('Please enter locality');
        }
        if( !empty($this->warehouse_images) ) {
            foreach ($this->warehouse_images as $key => $val) {
                $messages['warehouse_images.' . $key . '.max'] = 'The '.$val->getClientOriginalName() .' document exceeds the 2 Mb size.';
                $messages['warehouse_images.' . $key . '.mimes'] = 'The '.$val->getClientOriginalName() .' is not a ' . $this->settings['mimes'] . ' file.';
            }
        }

        return $messages;
    }

}
