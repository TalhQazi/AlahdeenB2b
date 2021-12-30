<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPaymentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_payment_log_id',
        'image_path'
    ];
}
