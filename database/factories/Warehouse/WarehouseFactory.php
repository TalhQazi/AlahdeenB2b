<?php

namespace Database\Factories\Warehouse;

use App\Models\City;
use App\Models\Locality;
use App\Models\User;
use App\Models\Warehouse\PropertyType;
use App\Models\Warehouse\Warehouse;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'city_id' => City::all()->random()->id,
            'locality_id' => Locality::all()->random()->id,
            'property_type_id' => PropertyType::all()->random()->id,
//            'city_id' => $this->faker->numberBetween(1, City::count()),
          'coordinates' => DB::raw('POINT(74.3715615,31.4635098)')
          ,
            'area' => rand(1000, 5000),
            'price' => rand(10000, 5000000),
            'can_be_shared' => rand(0, 1),
            'is_approved' => 1,
            'instant_booking' => rand(0, 1),
            'is_verified' => rand(0, 1),
            'is_active' => 1,
        ];
    }
}
