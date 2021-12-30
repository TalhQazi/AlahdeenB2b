<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'image_path',
        'title',
        'is_main'
    ];
}
