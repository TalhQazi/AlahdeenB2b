<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationSellerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'alternate_email',
        'phone',
        'address',
    ];
}
