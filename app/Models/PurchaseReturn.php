<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_code", "product_name", "return_quantity",
        "return_amount", "purchase_order_no", "invoice_no",
        "comments", "user_id", "product_id", "is_return_product"
    ];
}
