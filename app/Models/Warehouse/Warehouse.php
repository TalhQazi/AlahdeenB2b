<?php

namespace App\Models\Warehouse;

use App\Models\Locality;
use App\Models\User;
use App\Models\City;
use App\Models\WarehouseBooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SpatialTrait, SoftDeletes;

    protected $spatialFields = [
        'coordinates'
    ];

    protected $fillable = [
        'user_id',
        'property_type_id',
        'area',
        'price',
        'city_id',
        'locality_id',
        'coordinates',
        'can_be_shared',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function features()
    {
        return $this->hasMany(WarehouseFeature::class);
    }

    public function images()
    {
        return $this->hasMany(WarehouseImage::class)->orderBy('is_main','desc');
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function bookings()
    {
        return $this->hasMany(WarehouseBooking::class);
    }

}
