<?php

namespace Database\Seeders;

use FontLib\Table\Type\name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            'id' => 1,
            'name' => 'Cash Against Delivery (CAD)',
            'is_online' => 0,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 2,
            'name' => 'Cash on Delivery (COD)',
            'is_online' => 0,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 3,
            'name' => 'Cash Advance (CA)',
            'is_online' => 1,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 4,
            'name' => 'Cash in Advance (CID)',
            'is_online' => 1,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 5,
            'name' => 'Cheque',
            'is_online' => 0,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 6,
            'name' => 'Days after Acceptance (DA)',
            'is_online' => 0,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 7,
            'name' => 'Delivery Point (DP)',
            'is_online' => 0,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 8,
            'name' => 'Letter of Credit (L/C)',
            'is_online' => 1,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 9,
            'name' => 'Letter of Credit at Sight (Sight L/C)',
            'is_online' => 0,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 10,
            'name' => 'Telegraphic Transfer (T/T)',
            'is_online' => 1,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 11,
            'name' => 'Western Union',
            'is_online' => 1,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 12,
            'name' => 'Paypal',
            'is_online' => 1,
            'is_active'=> 1
        ]);

        DB::table('payment_methods')->insert([
            'id' => 13,
            'name' => 'Others',
            'is_online' => 0,
            'is_active'=> 1
        ]);
    }
}
