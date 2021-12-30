<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'quotation_id',
        'discount',
        'applicable_taxes',
        'shipping_taxes',
        'delivery_period',
        'payment_terns',
        'additional_info'
    ];
}
