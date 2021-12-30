<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class NotificationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'is_active'
    ];

    private static $cache_key = 'notification_types';

    public static function getAllActive()
    {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $notificationTypes = self::where('is_active', '=', true)->get();
            if (count($notificationTypes) > 0) {
                Cache::put(self::$cache_key, $notificationTypes, Carbon::now()->addMonth(1));
            }

            return $notificationTypes;
        }
    }
}
