<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProductInterest extends Model
{
    use HasFactory;

    protected $fillable = ['required_product', 'user_id', 'frequency', 'quantity', 'quantity_unit'];
}
