<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class ModeOfPayment extends Model
{
    use HasFactory;

    private static $cache_key = 'payment_methods';

    public function businessModeOfPayments() {
        return $this->hasMany(BusinessModeOfPayment::class);
    }

    public static function getModeOfPayments() {
        if (Cache::has(self::$cache_key)) {

            return Cache::get(self::$cache_key);
        } else {
            $modeOfPayments = self::all();
            if (count($modeOfPayments) > 0) {
                Cache::put(self::$cache_key, $modeOfPayments, Carbon::now()->addMonth(1));
            }

            return $modeOfPayments;
        }
    }
}
