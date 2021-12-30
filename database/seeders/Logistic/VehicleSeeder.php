<?php

namespace Database\Seeders\Logistic;

use Illuminate\Database\Seeder;
use App\Models\Logistics\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::create([
            'id' => 1,
            'name' => 'Motor Bike',
            'image_path' => 'images/vehicle/motor_bike.jpg',
            'parent_id' => 0
        ]);
        Vehicle::create([
            'id' => 2,
            'name' => 'Pickup',
            'image_path' => 'images/vehicle/pickup.jpg',
            'parent_id' => 0
        ]);
        Vehicle::create([
            'id' => 3,
            'name' => 'Pickup Open',
            'image_path' => 'images/vehicle/pickup_open.jpg',
            'parent_id' => 2
        ]);
        Vehicle::create([
            'id' => 4,
            'name' => 'Pickup Close',
            'image_path' => 'images/vehicle/pickup_close.jpg',
            'parent_id' => 2
        ]);
        Vehicle::create([
            'id' => 5,
            'name' => 'Shehzore',
            'image_path' => 'images/vehicle/shehzore.jpg',
            'parent_id' => 0
        ]);
        Vehicle::create([
            'id' => 6,
            'name' => 'Shehzore Open',
            'image_path' => 'images/vehicle/shehzore_open.jpg',
            'parent_id' => 5
        ]);
        Vehicle::create([
            'id' => 7,
            'name' => 'Shehzore Close',
            'image_path' => 'images/vehicle/shehzore_close.jpg',
            'parent_id' => 5
        ]);
        Vehicle::create([
            'id' => 8,
            'name' => 'Mazda',
            'image_path' => 'images/vehicle/Mazda.jpg',
            'parent_id' => 0
        ]);
        Vehicle::create([
            'id' => 9,
            'name' => 'Mazda Flatbed',
            'image_path' => 'images/vehicle/mazda_flatbed.jpg',
            'parent_id' => 8
        ]);
        Vehicle::create([
            'id' => 10,
            'name' => 'Mazda Open',
            'image_path' => 'images/vehicle/mazda_open.jpg',
            'parent_id' => 8
        ]);
        Vehicle::create([
            'id' => 11,
            'name' => 'Mazda Containerized',
            'image_path' => 'images/vehicle/mazda_containerized.jpg',
            'parent_id' => 8
        ]);
        Vehicle::create([
            'id' => 12,
            'name' => 'Mazda Commercial 16 Feet',
            'image_path' => 'images/vehicle/mazda_com_16_ft.jpg',
            'parent_id' => 8
        ]);
        Vehicle::create([
            'id' => 13,
            'name' => 'Mazda Commercial 20 Feet',
            'image_path' => 'images/vehicle/mazda_com_20_ft.jpg',
            'parent_id' => 8
        ]);
        Vehicle::create([
            'id' => 14,
            'name' => 'Heavy Vehicle',
            'image_path' => 'images/vehicle/heavy_vehicle.jpg',
            'parent_id' => 0
        ]);
        Vehicle::create([
            'id' => 15,
            'name' => 'Heavy Vehicle 20Ft Flatbed',
            'image_path' => 'images/vehicle/heavy_vehicle_20ft_fb.jpg',
            'parent_id' => 14
        ]);
        Vehicle::create([
            'id' => 16,
            'name' => 'Heavy Vehicle 20Ft Half Body',
            'image_path' => 'images/vehicle/heavy_vehicle_20ft_hb.jpg',
            'parent_id' => 14
        ]);
        Vehicle::create([
            'id' => 17,
            'name' => 'Heavy Vehicle 20Ft Containerized',
            'image_path' => 'images/vehicle/heavy_vehicle_20ft_containerized.jpg',
            'parent_id' => 14
        ]);
        Vehicle::create([
            'id' => 18,
            'name' => 'Heavy Vehicle 40Ft Flatbed',
            'image_path' => 'images/vehicle/heavy_vehicle_40ft_fb.jpg',
            'parent_id' => 14
        ]);
        Vehicle::create([
            'id' => 19,
            'name' => 'Heavy Vehicle 40Ft Half Body',
            'image_path' => 'images/vehicle/heavy_vehicle_40ft_hb.jpg',
            'parent_id' => 14
        ]);
        Vehicle::create([
            'id' => 20,
            'name' => 'Heavy Vehicle 40Ft Containerized',
            'image_path' => 'images/vehicle/heavy_vehicle_40ft_containerized.jpg',
            'parent_id' => 14
        ]);
        Vehicle::create([
            'id' => 21,
            'name' => 'Water Tanker',
            'image_path' => 'images/vehicle/water_tanker.jpg',
            'parent_id' => 0
        ]);
        Vehicle::create([
            'id' => 22,
            'name' => 'Water Tanker 1000 gal',
            'image_path' => 'images/vehicle/water_tanker_1k_gal.jpg',
            'parent_id' => 21
        ]);
        Vehicle::create([
            'id' => 23,
            'name' => 'Water Tanker 2000 gal',
            'image_path' => 'images/vehicle/water_tanker_2k_gal.jpg',
            'parent_id' => 21
        ]);
        Vehicle::create([
            'id' => 24,
            'name' => 'Water Tanker 3000 gal',
            'image_path' => 'images/vehicle/water_tanker_3k_gal.jpg',
            'parent_id' => 21
        ]);
        Vehicle::create([
            'id' => 25,
            'name' => 'Water Tanker 4000 gal',
            'image_path' => 'images/vehicle/water_tanker_4k_gal.jpg',
            'parent_id' => 21
        ]);
        Vehicle::create([
            'id' => 26,
            'name' => 'Water Tanker 5000 gal',
            'image_path' => 'images/vehicle/water_tanker_5k_gal.jpg',
            'parent_id' => 21
        ]);
        Vehicle::create([
            'id' => 27,
            'name' => 'Water Tanker 6000 gal',
            'image_path' => 'images/vehicle/water_tanker_6k_gal.jpg',
            'parent_id' => 21
        ]);
    }
}
