<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    use HasFactory;

    protected $connection = 'alahdeen_logistic';

    protected $fillable = [
        'booking_request_id',
        'lat',
        'lng',
    ];
}
