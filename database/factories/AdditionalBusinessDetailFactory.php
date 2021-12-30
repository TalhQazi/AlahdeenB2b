<?php

namespace Database\Factories;

use App\Models\AdditionalBusinessDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdditionalBusinessDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdditionalBusinessDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday'. 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $rand_key = array_rand($days);
        return [
            'logo' => asset('img/camera_icon.png'),
            'description' => '<p> Company Description </p>',
            'start_day' => 'Monday',
            'end_day' => 'Friday',
            'start_time' => '9 A.M.',
            'end_time' => '9 P.M.',
        ];
    }
}
