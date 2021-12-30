<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'photo_path'];
}
