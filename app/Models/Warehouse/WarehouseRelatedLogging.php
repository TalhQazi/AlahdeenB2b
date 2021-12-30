<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseRelatedLogging extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'model_type',
        'data',
        'user_id',
        'ip',
        'description',
        'action',
        'data'
    ];
}
