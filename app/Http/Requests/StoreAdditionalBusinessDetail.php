<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdditionalBusinessDetail extends FormRequest
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
            'logo' => ['file', 'nullable', 'max:200'],
            'description' => ['nullable', 'string'],
            'start_day' => ['string'],
            'end_day' => ['string'],
            'start_time' => ['string'],
            'end_time' => ['string'],
            'business_states' => ['nullable', 'array'],
            'business_states.*' => ['string'],
            'included_cities' => ['nullable', 'array'],
            'included_cities.*' => ['string'],
            'excluded_cities' => ['nullable', 'array'],
            'excluded_cities.*' => ['string'],
            'mode_of_payments' => ['nullable', 'array'],
            'mode_of_payments.*' => ['integer'],
            'company_photo' => ['nullable', 'array'],
            'company_photo.*' => ['file', 'max:200'],
            'company_id' => ["nullable", 'string'],
            'import_export_no' => ["nullable", 'string'],
            'bank_name' => ["nullable", 'string'],
            'income_tax_number' => ["nullable", 'string'],
            'ntn' => ["nullable", 'numeric', 'digits:7'],
            'no_of_production_units' => ["nullable", 'string'],
            'affiliation_memberships' => ["nullable", 'string'],
            'company_branches' => ["nullable", 'string'],
            'owner_cnic' => ["nullable", 'string'],
            'infrastructure_size' => ["nullable", 'string'],
            'cities_to_trade_with' => ['nullable', 'array'],
            'cities_to_trade_with.*' => ['string'],
            'cities_to_trade_from' => ['nullable', 'array'],
            'cities_to_trade_from.*' => ['string'],
            'shipment_modes' => ['nullable', 'array'],
            'shipment_modes.*' => ['string'],
            'arn_no' => ["nullable", 'string']
        ];
    }

    public function messages()
    {
        $messages = [];

        if (!empty($this->logo)) {
            $messages['logo.max'] = 'The ' . $this->logo->getClientOriginalName() . ' image exceeds the 200 Kb size.';
            $messages['logo.mimes'] = 'The ' . $this->logo->getClientOriginalName() . ' is not a png, jpg or jpeg file.';

        } else if (!empty($this->company_photo)) {
            foreach ($this->company_photo as $key => $val) {
                $messages['company_photo.' . $key . '.max'] = 'The ' . $val->getClientOriginalName() . ' image exceeds the 200 Kb size.';
                $messages['company_photo.' . $key . '.mimes'] = 'The ' . $val->getClientOriginalName() . ' is not a png, jpg or jpeg file.';
            }
        }

        return $messages;
    }


}
