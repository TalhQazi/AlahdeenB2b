<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_method_id',
        'promo_code',
        'total_amount',
        'total_tax',
        'total_discount',
        'notes',
    ];

    public $payment_status = [
        'pending',
        'paid',
        'cancelled',
        'refunded'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function subscriptionPaymentLog()
    {
        return $this->hasMany(SubscriptionPaymentLog::class);
    }

    public function subscriptionPaymentImages()
    {
        $this->hasManyThrough(SubscriptionPaymentImages::class, SubscriptionPaymentLog::class);
    }
}
