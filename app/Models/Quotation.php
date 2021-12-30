<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'quotation_path'
    ];

    public function products()
    {
        return $this->hasMany(QuotationProduct::class);
    }

    public function terms()
    {
        return $this->hasOne(QuotationTerm::class);
    }

    public function sellerDetails()
    {
        return $this->hasOne(QuotationSellerDetail::class);
    }
}
