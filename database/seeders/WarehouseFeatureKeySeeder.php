<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse\WarehouseFeatureKey as FeatureKey;

class WarehouseFeatureKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FeatureKey::create(['key' => 'washrooms', 'key_type' => 'string']);
        FeatureKey::create(['key' => 'fans', 'key_type' => 'string']);
        FeatureKey::create(['key' => 'lights', 'key_type' => 'string']);
        FeatureKey::create(['key' => 'rooms', 'key_type' => 'string']);
        FeatureKey::create(['key' => 'parking spaces', 'key_type' => 'string']);
        FeatureKey::create(['key' => 'electrical backup', 'key_type' => 'boolean']);
        FeatureKey::create(['key' => 'waste disposal', 'key_type' => 'boolean']);
        FeatureKey::create(['key' => 'refrigerator facility (cold storage)', 'key_type' => 'boolean']);
        FeatureKey::create(['key' => 'shelves', 'key_type' => 'boolean']);
        FeatureKey::create(['key' => 'safety', 'key_type' => 'boolean']);
        FeatureKey::create(['key' => 'security', 'key_type' => 'boolean']);
    }
}
