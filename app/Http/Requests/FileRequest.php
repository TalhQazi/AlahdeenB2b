<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileRequest extends FormRequest
{

    private $settings;

    public function __construct()
    {
        $this->settings =  config('document_configuration.default');
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
            'uploads' => ['array'],
            'uploads.*' => ['required', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']]
        ];
    }

    public function messages()
    {
        $messages = [];

        foreach ($this->uploads as $key => $val) {
            $messages['uploads.' . $key . '.max'] = __('Uploaded document size can not exceed ' . $this->settings['max'] . ' Kb');
            $messages['uploads.' . $key . '.mimes'] = __('Uploaded document can only be of type '.$this->settings['mimes']);
        }

        return $messages;
    }

}
