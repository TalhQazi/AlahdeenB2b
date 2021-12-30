<?php

namespace Database\Factories\Warehouse;

use App\Models\Warehouse\WarehouseFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFeatureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WarehouseFeature::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'feature_id' => rand(1, 11),
            'feature' => rand(1, 10)
        ];

    }
}
