<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Inventory\ProductDefinition;
use App\Models\Inventory\ProductPricing;
use App\Models\User;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\ProductVideo;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title', 'description', 'category_id', 'user_id', 'price', 'category'];

    public function productCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(ProductDetail::class,'product_id','id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id','id')->orderBy('is_main', 'desc');
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class,'product_id','id');
    }

    public function documents()
    {
        return $this->hasMany(ProductDocument::class,'product_id','id');
    }

    public function company() {
        return $this->hasOneThrough(BusinessDetail::class, User::class, 'id', 'user_id', 'user_id');
    }

    public function productServices() {
        return $this->hasManyThrough(UserProductService::class, User::class, 'id', 'user_id', 'user_id');
    }

    public function inventoryDefinition()
    {
        return $this->hasOne(ProductDefinition::class);
    }

    public function inventoryPricing()
    {
        return $this->hasOne(ProductPricing::class);
    }

    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class);
    }

    public function promotionAgainst()
    {
        return $this->hasOne(PromotionalProduct::class, 'product_id', 'id')->where('is_active', 1);
    }

    public function promotionalProduct()
    {
        return $this->hasMany(PromotionalProduct::class, 'promotional_product_id', 'id')->where('is_active', 1);
    }



}
