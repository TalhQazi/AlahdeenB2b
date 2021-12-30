<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'seller_id',
        'product',
        'quantity',
        'unit',
        'price'
    ];


}
