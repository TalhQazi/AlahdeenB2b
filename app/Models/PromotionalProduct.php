<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'promotional_product_id',
        'discount_percentage',
        'by_date',
        'by_no_of_units',
        'start_date',
        'end_date',
        'no_of_units',
        'description',
    ];

    public function promotionAgainst()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productOnPromotion()
    {
        return $this->belongsTo(Product::class, 'promotional_product_id', 'id');
    }
}
