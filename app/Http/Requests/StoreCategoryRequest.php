<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{

    private $settings;


    public function __construct()
    {

        $this->settings =  config('images_configuration.category', config('images_configuration.default'));
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
            'title' => ['required', 'string', 'max:255'],
            'parent_cat_id' => ['nullable', 'numeric'],
            'image_path' => ['file', 'nullable'],
        ];
    }

    public function messages()
    {
        $messages = [];
        if( !empty($this->parent_cat_id) ) {
            $messages['parent_cat_id.numeric'] = __('Please select valid parent category');
        }
        if( !empty($this->image_path) ) {
            // $allowedMimesType = str_replace('mimes:','',$this->mimeTypes);
            $messages['image_path.max'] = __('Category image size can not exceed ' . $this->settings['max'] . ' Kb');
            $messages['image_path.mimes'] = __('Category image can only be of type '.$this->settings['mimes']);
        }

        return $messages;
    }
}
