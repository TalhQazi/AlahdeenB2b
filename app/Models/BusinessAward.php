<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'title',
        'award_image'
    ];

}
