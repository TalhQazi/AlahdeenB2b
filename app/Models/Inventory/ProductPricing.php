<?php

namespace App\Models\Inventory;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
    use HasFactory;

    protected $table = 'inventory_product_pricings';

    protected $fillable = [
        'product_id',
        'total_units',
        'price_per_unit',
        'avg_cost_per_unit',
        'sales_tax_percentage',
        'allow_below_cost_sale',
        'allow_price_change',
        'discount_percentage',
        'promotional_product_id',
        'promotional_discount_percentage',
        'promotion_description'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
