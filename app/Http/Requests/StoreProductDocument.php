<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductDocument extends FormRequest
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
            'product_documents' => ['array'],
            'product_documents.*' => ['file', 'max: 2048'],
        ];
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->product_documents as $key => $val) {
           $messages['product_documents.' . $key . '.max'] = 'The '.$val->getClientOriginalName() .' document exceeds the 2 Mb size.';
           $messages['product_documents.' . $key . '.mimes'] = 'The '.$val->getClientOriginalName() .' is not a pdf file.';
        }

        return $messages;
    }
}
