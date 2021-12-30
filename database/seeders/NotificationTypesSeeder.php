<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationType::create(['id' => 1, 'title' => 'Stock Alerts', 'slug' => 'stock_alerts']);
        NotificationType::create(['id' => 2, 'title' => 'invoice', 'slug' => 'invoice']);
        NotificationType::create(['id' => 3, 'title' => 'quotation', 'slug' => 'quotation']);
    }
}
