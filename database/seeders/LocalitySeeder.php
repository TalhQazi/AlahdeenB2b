<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Locality;
use Illuminate\Support\Facades\DB;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class LocalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tap(Locality::create([
            'name' => 'DHA PHASE 4',
            'city_id' => 2,
            'coordinates' => DB::raw('POINT(74.375316,31.4633254)')
            // 'coordinates' => new POINT(74.375316,31.4633254)
        ]), function(Locality $locality) {
            Locality::create([
                'name' => 'Sector AA',
                'city_id' => 2,
                'parent_id' => $locality->id,
                'coordinates' => DB::raw('POINT(74.3672544,31.4604087)')
            ]);

            Locality::create([
                'name' => 'Sector BB',
                'city_id' => 2,
                'parent_id' => $locality->id,
                'coordinates' => DB::raw('POINT(74.3715615,31.4635098)')
            ]);
        });


        tap(Locality::create([
            'name' => 'New Garden Town',
            'city_id' => 2,
            'coordinates' => DB::raw('POINT(74.3125414, 31.5048995)')
        ]), function(Locality $locality) {
            Locality::create([
                'name' => 'Ali Block',
                'city_id' => 2,
                'parent_id' => $locality->id,
                'coordinates' => DB::raw('POINT(74.3221898,31.5029145)')//31.5156538,74.3167575
            ]);
        });
    }
}
