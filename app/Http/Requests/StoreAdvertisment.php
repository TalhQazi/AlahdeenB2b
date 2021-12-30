<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdvertisment extends FormRequest
{
    private $settings;


    public function __construct()
    {

        $this->settings =  config('images_configuration.advertisment', config('images_configuration.default'));
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
        $rules =  [
            'url_link' => ['nullable', 'string'],
            'display_order' => ['required', 'integer'],
            'is_active' => ['nullable', Rule::in(['on'])],
            'display_section' => ['required', Rule::in(config('display_configuration.advertisments'))]
        ];

        if($this->isMethod('PUT')) {
            $rules['image_path'] = ['file', 'max:'.$this->settings['max']];
        } else {
            $rules['image_path'] = ['required', 'file', 'max:'.$this->settings['max']];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        // dd($this->image_path);
        if( !empty($this->image_path) ) {
            $messages['image_path.max'] = __('Advertisment image size can not exceed ' . $this->settings['max'] . ' Kb');
            $messages['image_path.mimes'] = __('Advertisment image can only be of type '.$this->settings['mimes']);
        } else {
            $messages['image_path.required'] = __('Advertisment image needs to be uploaded');
        }

        return $messages;
    }
}
