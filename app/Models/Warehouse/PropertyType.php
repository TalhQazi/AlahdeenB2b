<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class PropertyType extends Model
{
    use HasFactory;

    private static $cache_key = 'property_types';

    public static function getPropertyTypes() {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $propertyTypes = self::all();
            if (count($propertyTypes) > 0) {
                Cache::put(self::$cache_key, $propertyTypes, Carbon::now()->addMonth(1));
            }

            return $propertyTypes;
        }
    }
}
