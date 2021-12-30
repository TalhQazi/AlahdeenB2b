<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function favorable()
    {
        return $this->morphTo();
    }
}
