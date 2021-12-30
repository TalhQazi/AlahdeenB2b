<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_id',
        'ref_text',
        'ref_image_document',
        'transaction_date',
        'payment_method_id',
        'payment_status',
        'amount',
        'updated_by',
        'bank_account_id',
        'is_closed',
        'payment_id',
        'payment_type',
    ];

    public function payment()
    {
        return $this->morphTo();
    }
}
