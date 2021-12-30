<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessType;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessType::create(['business_type' => 'Manufacturer']);
        BusinessType::create(['business_type' => 'Exporter']);
        BusinessType::create(['business_type' => 'Importer']);
        BusinessType::create(['business_type' => 'Distributor']);
        BusinessType::create(['business_type' => 'Supplier']);
        BusinessType::create(['business_type' => 'Trader']);
        BusinessType::create(['business_type' => 'Wholesaler']);
        BusinessType::create(['business_type' => 'Retailer']);
        BusinessType::create(['business_type' => 'Dealer']);
        BusinessType::create(['business_type' => 'Fabricator']);
        BusinessType::create(['business_type' => 'Producer']);
        BusinessType::create(['business_type' => 'Service Provider']);
    }
}
