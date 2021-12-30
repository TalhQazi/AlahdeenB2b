<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'code',
      'quantity',
      'quantity_unit',
      'rate',
      'gst',
      'product_id',
    ];
}
