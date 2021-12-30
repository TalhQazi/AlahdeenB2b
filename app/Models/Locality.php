<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class Locality extends Model
{
    use HasFactory, SpatialTrait;
    private static $cache_key = 'localities';

    public static function getLocalities() {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $Localities = self::all();
            if (count($Localities) > 0) {
                Cache::put(self::$cache_key, $Localities, Carbon::now()->addMonth(1));
            }

            return $Localities;
        }
    }

    protected $spatialFields = [
        'coordinates'
    ];

    protected $fillable = [
        'name',
        'coordinates',
        'parent_id',
        'city_id'
    ];

    public function parentLocalities()
    {
        return $this->hasOne(Locality::class, 'id', 'parent_id');
    }

    public function childrenLocalities()
    {
        return $this->hasMany(Locality::class, 'parent_id', 'id');
    }

}
