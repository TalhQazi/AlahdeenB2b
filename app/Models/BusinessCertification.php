<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'certification',
        'membership',
        'image_path'
    ];
}
