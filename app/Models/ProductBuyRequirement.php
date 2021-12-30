<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBuyRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_ids',
        'required_product',
        'requirement_details',
        'quantity',
        'unit',
        'budget',
        'requirement_urgency',
        'requirement_frequency',
        'supplier_location',
        'image_path',
        'terms_and_conditions'
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function favorites()
    {
        return $this->morphMany(UserFavorite::class, 'favorable');
    }

    public function quotation()
    {
        return $this->hasOne(LeadQuotation::class,'lead_id','id');
    }
}
