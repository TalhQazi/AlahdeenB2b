<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropertyType::create(['title' => 'Sheltered']);
        PropertyType::create(['title' => 'Shop']);
        PropertyType::create(['title' => 'Apartment']);
        PropertyType::create(['title' => 'Plaza']);
        PropertyType::create(['title' => 'Single Storey']);
        PropertyType::create(['title' => 'Double Storey']);
    }
}
