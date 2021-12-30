<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'address',
        'locality',
        'city_id',
        'zip_code',
        'phone_number',
        'alternate_website',
        'year_of_establishment',
        'no_of_employees',
        'annual_turnover',
        'ownership_type'
    ];

    public function additionalDetails() {
        return $this->hasOne(AdditionalBusinessDetail::class, 'business_id', 'id');
    }

    public function businessCity() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function businessModeOfPayments() {
        return $this->hasMany(BusinessModeOfPayment::class, 'business_id', 'id');
    }

    public function businessPhotos() {
        return $this->hasMany(BusinessPhoto::class, 'business_id', 'id');
    }


    public function businessContacts() {
        return $this->hasMany(BusinessContactDetail::class, 'business_id', 'id');
    }

    public function businessCertificates() {
        return $this->hasMany(BusinessCertification::class, 'business_id', 'id');
    }

    public function businessAwards() {
        return $this->hasMany(BusinessAward::class, 'business_id', 'id');
    }

    public function productServices() {
      return $this->hasManyThrough(UserProductService::class, User::class, 'id', 'user_id', 'user_id');
    }

    public function director() {
        return $this->hasOneThrough(BusinessDirector::class, User::class, 'id', 'user_id', 'user_id');
    }
}

