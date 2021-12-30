<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideOffer extends Model
{
    use HasFactory;

    protected $connection = 'alahdeen_logistic';

    protected $fillable = [
        'booking_request_id',
        'driver_id',
        'offer',
    ];
}
