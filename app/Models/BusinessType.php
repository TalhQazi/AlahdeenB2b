<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BusinessType extends Model
{
    use HasFactory;

    private static $cache_key = 'business_types';

    public static function getBusinessTypes() {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $businessTypes = self::all();
            if (count($businessTypes) > 0) {
                Cache::put(self::$cache_key, $businessTypes, Carbon::now()->addMonth(1));
            }

            return $businessTypes;
        }
    }
}
