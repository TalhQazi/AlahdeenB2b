<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatterSheetProduct extends Model
{
    use HasFactory;

    protected $fillable = ['image_path','title', 'description', 'category_id', 'user_id', 'price', 'product_code', 'web_category', 'brand_name', 'approx_price', 'currency_1', 'min_price', 'max_price', 'currency_2', 'min_order_quantity', 'unit_measure_quantity', 'supply_ability', 'unit_measure_supply'];
}
