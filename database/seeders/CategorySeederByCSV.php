<?php

namespace Database\Seeders;

use App\Helpers\LargeCSVReader;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CategorySeederByCSV extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $file = fopen(database_path('seeders/csv/categories.csv'), 'r');

    $csv_reader = new LargeCSVReader($file, ",");

    $cur_time = Date(now());

    foreach ($csv_reader->csvToArray() as $data) {
      // Preprocessing of the array.
      foreach ($data as $key => $entry) {
        // Laravel doesn't add timestamps on its own when inserting in chunks.
        $data[$key]['created_at'] = $cur_time;
        $data[$key]['updated_at'] = $cur_time;
        $data[$key]['image_path'] = 'public/category/images/'.$data[$key]['id'].'.jpg';
      }
      Category::insertOrIgnore($data);
    }
  }
}
