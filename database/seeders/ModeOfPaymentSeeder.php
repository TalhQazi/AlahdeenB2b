<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModeOfPayment;

class ModeOfPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModeOfPayment::create(['mode_of_payment' => 'Cash Against Delivery (CAD)']);
        ModeOfPayment::create(['mode_of_payment' => 'Cash on Delivery (COD)']);
        ModeOfPayment::create(['mode_of_payment' => 'Cash Advance (CA)']);
        ModeOfPayment::create(['mode_of_payment' => 'Cash in Advance (CID)']);
        ModeOfPayment::create(['mode_of_payment' => 'Cheque']);
        ModeOfPayment::create(['mode_of_payment' => 'Days after Acceptance (DA)']);
        ModeOfPayment::create(['mode_of_payment' => 'Delivery Point (DP)']);
        ModeOfPayment::create(['mode_of_payment' => 'Letter of Credit (L/C)']);
        ModeOfPayment::create(['mode_of_payment' => 'Letter of Credit at Sight (Sight L/C)']);
        ModeOfPayment::create(['mode_of_payment' => 'Telegraphic Transfer (T/T)']);
        ModeOfPayment::create(['mode_of_payment' => 'Western Union']);
        ModeOfPayment::create(['mode_of_payment' => 'Paypal']);
        ModeOfPayment::create(['mode_of_payment' => 'Others']);

    }
}
