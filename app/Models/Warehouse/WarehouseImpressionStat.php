<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseImpressionStat extends Model
{
    use HasFactory;

    protected $connection = 'stats';

    protected $fillable = [
      'warehouse_id'
    ];
}
