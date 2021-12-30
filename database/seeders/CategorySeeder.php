<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['title' => 'Electronics', 'image_path' => 'category']);
        Category::create(['title' => 'Medical Equipments', 'image_path' => 'category']);
        Category::create(['title' => 'Laptops', 'image_path' => 'category']);
        Category::create(['title' => 'Mobiles', 'image_path' => 'category']);
    }
}
