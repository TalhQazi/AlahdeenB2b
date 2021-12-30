<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      foreach(config('badges') as $badge) {
        Badge::create([
          'id' => $badge['id'],
          'name' => $badge['name'],
          'image' => $badge['image']
        ]);
      }
    }
}
