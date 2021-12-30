<?php

namespace App\Http\Requests\Logistics;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    private $settings;

    public function __construct()
    {
        $this->settings =  config('images_configuration.logistics');
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
            'image_path' => ['required', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']],
            'dob' => ['required', 'date', 'date_format:Y-m-d'],
            'license_no' => ['required', 'string'],
            'license_path' => ['required', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']],
            'date_of_expiry' => ['required', 'date', 'date_format:Y-m-d', 'after:'.Carbon::today()->isoFormat('Y-m-d')],
            'cnic_front_path' => ['required', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']],
            'cnic_back_path' => ['required', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']],
            'referral_code' => ['nullable', 'string'],
            'vehicle_id' => ['required'],
            'company' => ['required', 'string'],
            'number_plate_no' => ['required', 'string'],
            'vehicle_image' => ['required', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']],
            'vehicle_reg_certificate' => ['required', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']],
            'vehicle_health_certificate' => ['required', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']]
        ];


        if ($this->wantsJson()) {

            if($this->isMethod('POST')) {
                $this->changeRules($rules, 'post', true);
            } else {
                $this->changeRules($rules, 'put', true);
            }

        } else {

            if($this->isMethod('PUT')) {
                $this->changeRules($rules, 'put');
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        if( !empty($this->image_path) ) {
            $messages['image_path.max'] = __('Your photo size can not exceed '. $this->settings['max'] .' Kb');
            $messages['image_path.mimes'] = __('Your photo can only be of type '.$this->settings['mimes']);
        } else {
            $messages['image_path.required'] = __('Your photo is required');
        }

        if( !empty($this->cnic_front_path) ) {
            $messages['cnic_front_path.max'] = __('Your CNIC front side photo size can not exceed '. $this->settings['max'] .' Kb');
            $messages['cnic_front_path.mimes'] = __('Your CNIC front side photo can only be of type '.$this->settings['mimes']);
        } else {
            $messages['cnic_front_path.required'] = __('Your CNIC front side photo is required');
        }

        if( !empty($this->cnic_back_path) ) {
            $messages['cnic_back_path.max'] = __('Your CNIC back side photo size can not exceed '. $this->settings['max'] .' Kb');
            $messages['cnic_back_path.mimes'] = __('Your CNIC back side photo can only be of type '.$this->settings['mimes']);
        } else {
            $messages['cnic_back_path.required'] = __('Your CNIC back side photo is required');
        }

        return $messages;

    }

    public function attributes()
    {
        return [
            'dob' => 'date of birth',
            'license_no' => 'license Number',
            'vehicle_id' => 'vehicle type',
            'vehicle_reg_certificate' => 'vehicle registration certificate'
        ];
    }

    private function changeRules(&$rules, $method = 'post', $isApi = false)
    {
        if($isApi) {

            if($method == 'post') {
                $rules['image_path'] = ['required', 'string'];
                $rules['license_path'] = ['required', 'string'];
                $rules['cnic_front_path'] = ['required', 'string'];
                $rules['cnic_back_path'] = ['required', 'string'];
                $rules['vehicle_image'] = ['required', 'string'];
                $rules['vehicle_reg_certificate'] = ['required', 'string'];
                $rules['vehicle_health_certificate'] = ['required', 'string'];
            } else {
                $rules['image_path'] = ['nullable', 'string'];
                $rules['license_path'] = ['nullable', 'string'];
                $rules['cnic_front_path'] = ['nullable', 'string'];
                $rules['cnic_back_path'] = ['nullable', 'string'];
                $rules['vehicle_image'] = ['nullable', 'string'];
                $rules['vehicle_reg_certificate'] = ['nullable', 'string'];
                $rules['vehicle_health_certificate'] = ['nullable', 'string'];
            }

        } else {
            if($method == 'put') {
                $rules['image_path'] = ['nullable', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']];
                $rules['license_path'] = ['nullable', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']];
                $rules['cnic_front_path'] = ['nullable', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']];
                $rules['cnic_back_path'] = ['nullable', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']];
                $rules['vehicle_image'] = ['nullable', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']];
                $rules['vehicle_reg_certificate'] = ['nullable', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']];
                $rules['vehicle_health_certificate'] = ['nullable', 'image', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']];
            }
        }
    }
}
