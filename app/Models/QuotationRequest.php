<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
    ];

    public function details()
    {
        return $this->hasMany(QuotationRequestDetail::class);
    }
}
