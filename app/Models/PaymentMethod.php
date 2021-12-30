<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'is_online', 'is_active'];

    private static $cache_key = 'payment_methods';

    public static function getAllActive()
    {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $paymentMethods = self::where('is_active', '=', true)->get();
            if (count($paymentMethods) > 0) {
                Cache::put(self::$cache_key, $paymentMethods, Carbon::now()->addMonth(1));
            }

            return $paymentMethods;
        }
    }
}
