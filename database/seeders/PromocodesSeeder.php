<?php

namespace Database\Seeders;

use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Database\Seeder;

class PromocodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Promocodes::createDisposable(
            1,
            100,
            [
                'is_fixed' => true,
            ],
        );
    }
}
