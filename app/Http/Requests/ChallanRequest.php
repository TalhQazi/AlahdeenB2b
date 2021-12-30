<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChallanRequest extends FormRequest
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
        'client_id' => [
          'required',
          Rule::exists('clients')->where(function ($query) {
            return $query->where('user_id', Auth::user()->id);
          }),
        ],
        'challan_date' => ['required', 'date', 'date_format:Y-m-d'],
        'items_included' => ['required', 'string', 'max:255'],
        'no_of_pieces' => ['required', 'integer'],
        'weight' => ['required', 'string'],
        'bilty_no' => ['required', 'string'],
        'courier_name' => ['required', 'string'],
        'digital_signature' => ['file', 'nullable', 'max:500'],
      ];
    }
}
