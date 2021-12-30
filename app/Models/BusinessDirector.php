<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDirector extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'name',
        'designation',
        'description',
        'director_photo'
    ];
}


