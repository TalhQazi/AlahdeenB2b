<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_name',
        'designation',
        'company_name',
        'company_website',
        'message',
        'image_path'
    ];
}
