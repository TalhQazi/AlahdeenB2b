<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonial extends FormRequest
{

    private $settings;


    public function __construct()
    {

        $this->settings =  config('images_configuration.testimony', config('images_configuration.default'));
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
        $rules = [
            'user_name' => ['required', 'string'],
            'designation' => ['required', 'string'],
            'company_name' => ['nullable', 'string'],
            'company_website' => ['nullable', 'string'],
            'message' => ['required', 'string'],
        ];

        if($this->isMethod('PUT')) {
            $rules['image_path'] = ['file', 'max:'.$this->settings['max']];
        } else {
            $rules['image_path'] = ['required', 'file','max:'.$this->settings['max']];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        if( !empty($this->image_path) ) {
            $messages['image_path.max'] = __('Image size can not exceed '. $this->settings['max'] .' Kb');
            $messages['image_path.mimes'] = __('Image can only be of type '.$this->settings['mimes']);
        }

        return $messages;
    }

    public function attributes()
    {
        return [
            'image_path' => 'image',
        ];
    }
}
