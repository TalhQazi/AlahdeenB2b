<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
      'number',
      'po_date',
      'po_delivery_date',
      'attachment',
      'order_description',
      'payment_details',
      'created_by',
      'purchase_order_path'
    ];

    public function createdBy()
    {
      return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
