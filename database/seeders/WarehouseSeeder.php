<?php

namespace Database\Seeders;

use App\Models\Warehouse\Warehouse;
use App\Models\Warehouse\WarehouseFeature;
use App\Models\Warehouse\WarehouseFeatureKey;
use App\Models\Warehouse\WarehouseImage;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $featureKeys = WarehouseFeatureKey::all();
        Warehouse::factory()->count(10)->create()->each(function($warehouse) use ($featureKeys) {
            foreach($featureKeys as $featureKey) {

                $warehouse->features()->save(WarehouseFeature::factory()->create([
                    'warehouse_id' => $warehouse->id,
                    'feature_id' => $featureKey->id,
                    'feature' => $featureKey->key_type == 'boolean' ? rand(0, 1) : rand(1, 10)
                ]));
            }
            $warehouse->images()->save(WarehouseImage::factory()->make());
        });
    }
}
