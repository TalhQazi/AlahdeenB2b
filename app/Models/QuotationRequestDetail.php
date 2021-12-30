<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationRequestDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product',
        'budget',
        'quantity',
        'unit',
        'requirements'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentRequest()
    {
        return $this->belongsTo(QuotationRequest::class);
    }
}
