<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::create(['title' => 'Important']);
        Label::create(['title' => 'Contacted']);
        Label::create(['title' => 'Follow Up']);
        Label::create(['title' => 'Deal Done']);
        Label::create(['title' => 'Irrelevant']);
        Label::create(['title' => 'Negotiation']);
        Label::create(['title' => 'Quotation Sent']);
    }
}
