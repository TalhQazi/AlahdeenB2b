<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverFeedback extends Model
{
    use HasFactory;

    protected $connection = 'alahdeen_logistic';

    protected $fillable = [
        'booking_request_id',
        'rating',
        'feedback'
    ];

}
