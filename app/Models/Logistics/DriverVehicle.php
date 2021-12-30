<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model
{
    use HasFactory;

    protected $connection = 'alahdeen_logistic';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'company_name',
        'number_plate_no',
        'photo',
        'registration_certificate',
        'fitness_certificate',
    ];

    // public function driver()
    // {
    //     return $this->has
    // }
}
