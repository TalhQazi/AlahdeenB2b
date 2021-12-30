<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Label extends Model
{
    use HasFactory;

    private static $cache_key = 'labels';

    public static function getAllLabels() {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $labels = self::all();
            if (count($labels) > 0) {
                Cache::put(self::$cache_key, $labels, Carbon::now()->addMonth(1));
            }

            return $labels;
        }
    }
}
