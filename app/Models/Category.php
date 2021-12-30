<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;


class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title','image_path','parent_cat_id', 'level', 'bread_crumb'];

    public function parentCategory()
    {
        return $this->belongsTo(Category::class,'parent_cat_id','id');
    }

    public function allParentCategories()
    {
        return $this->parentCategory()->with('allParentCategories');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subCategories() {
        return $this->hasMany(Category::class, 'parent_cat_id', 'id');
    }

    public function allChildCategories() {
        return $this->hasMany(Category::class,'parent_cat_id')->with('subCategories');
    }

    public function trending() {
        return $this->hasOne(TrendingCategory::class);
    }

    public function homePageCategory() {
        return $this->hasOne(HomePageCategory::class, 'category_id', 'id');
    }


}
