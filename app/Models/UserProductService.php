<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProductService extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'business_type_id', 'keywords', 'is_primary'];

    public function businessType() {
        return $this->belongsTo(BusinessType::class, 'business_type_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
