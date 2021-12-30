<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function Livewire\str;

class StoreBookingRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'integer', Rule::exists("App\Models\Logistics\Vehicle", "id")],
            'child_vehicle_id' => ['nullable', 'integer', Rule::exists("App\Models\Logistics\Vehicle", "id")],
            'delivery_type' => ['required', 'string'],
            'pick_up_city_id' => ['required', 'integer'],
            'shipper_name' => ['required', "string"],
            'shipper_contact_number' => ['required', "string"],
            'shipper_address' => ['required', "string"],
            // 'shipper_lat' => ['required', "numeric"],
            // 'shipper_lng' => ['required', "numeric"],
            'drop_off_city_id' => ['required', 'integer'],
            'receiver_name' => ['required', "string"],
            'receiver_contact_number' => ['required', "string"],
            'receiver_address' => ['required', "string"],
            // 'receiver_lat' => ['required', "numeric"],
            // 'receiver_lng' => ['required', "numeric"],
            'detailed_description' => ['string'],
            'weight' => ['required', 'numeric'],
            'weight_unit' => ['required', 'string'],
            'volume' => ['required', "numeric"],
            'volume_unit' => ['required', 'string'],
            'departure_date' => ['required', 'date', 'date_format:Y-m-d'],
            'departure_time' => ['required', 'date_format:H:i'],
            'bid_offer' => ['required', 'numeric'],
            'comments_and_wishes' => ['string', 'nullable'],
            'terms_agreed' => ['required',  Rule::in(['on'])],
            'pick_up_country' => ['string', 'nullable'],
            'drop_off_country' => ['string', 'nullable'],
            'item.*' => ['required', 'array'],
            'item.*.product_id' => ['required', 'integer'],
            'item.*.type_of_packing' => ['required', 'string'],
            'item.*.no_of_packs' => ['required', 'integer'],
            'item.*.description' => ['required', 'string'],
        ];
    }
}
