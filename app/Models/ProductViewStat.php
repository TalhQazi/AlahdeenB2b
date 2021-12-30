<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductViewStat extends Model
{
    use HasFactory;

    protected $connection = 'stats';

    protected $fillable = [
        'product_id'
    ];
}
