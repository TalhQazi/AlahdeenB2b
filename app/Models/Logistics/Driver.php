<?php

namespace App\Models\Logistics;

use App\Models\Logistics\DriverLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $connection = 'alahdeen_logistic';

    protected $fillable = [
        'user_id',
        'image',
        'dob',
        'license_number',
        'license_photo',
        'license_expiry_date',
        'cnic_front',
        'cnic_back',
        'referral_code',
        'is_verified'
    ];

    public function vehicle()
    {
        return $this->hasOne(DriverVehicle::class, 'driver_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function location()
    {
        return $this->hasOne(DriverLocation::class, 'driver_id', 'id');
    }
}
