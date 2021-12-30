<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
    Model responsible for maintaining subscription order payment and status history
*/
class SubscriptionPaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_order_id',
        'description',
        'old_status',
        'added_by',
        'amount',
        'is_closed'
    ];

    public function paymentImages()
    {
        return $this->hasMany(SubscriptionPaymentImage::class);
    }
}
