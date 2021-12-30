<?php

namespace Database\Factories\Warehouse;

use App\Models\Warehouse\WarehouseImage;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseImageFactory extends Factory
{
    use ImageTrait;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WarehouseImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'image_path' => asset('img/warehouse.jpg'),
            'title' => 'warehouse image',
            'is_main' => rand(0 , 1)
        ];
    }
}
