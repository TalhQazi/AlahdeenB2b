<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularWarehouse extends Model
{
    use HasFactory;

    protected $fillable = [
      'warehouse_id'
    ];
}
