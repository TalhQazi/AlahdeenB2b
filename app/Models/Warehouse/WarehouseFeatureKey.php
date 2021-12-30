<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class WarehouseFeatureKey extends Model
{
    use HasFactory;

    private static $cache_key = 'features';

    public static function getFeatures()
    {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $features = self::where('status','=',1)->orderBy('key_type', 'asc')->get();
            if (count($features) > 0) {
                Cache::put(self::$cache_key, $features, Carbon::now()->addMonth(1));
            }

            return $features;
        }
    }
}
