<?php

namespace App\Models\Inventory;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDefinition extends Model
{
    use HasFactory;

    protected $table = 'inventory_product_definitions';

    protected $fillable = [
        "product_id",
        "product_code",
        "brand_name",
        "purchase_unit",
        "product_group",
        "purchase_type",
        "conversion_factor",
        "product_gender",
        "value_addition",
        "life_type",
        "tax_code",
        "supplier",
        "accquire_type",
        "additional_attributes",
        "technical_details",
        "additional_description",
        "purchase_production_interval",
        "purchase_production_unit"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
