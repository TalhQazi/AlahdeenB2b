<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'display_section',
        'display_order'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
