<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\BusinessDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusinessDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_name' => $this->faker->name,
            'address' => $this->faker->address,
            'locality' => Str::random(10),
            'city_id' => rand(1,10),
            'zip_code' => rand(10000,50000),
            'phone_number' => $this->faker->phoneNumber,
            'alternate_website' => 'https://'.Str::random(10).'.com',
            'year_of_establishment' => rand(1970, 2021),
            'no_of_employees' => rand(1, 1000),
            'annual_turnover' => rand(10000, 100000),
            'ownership_type' => 'Private Limited Company'
        ];
    }
}
