<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    protected $connection = 'alahdeen_logistic';

    protected $fillable = [
        'vechicle_id',
        'delivery_type',
        'pick_up_city_id',
        'shipper_name',
        'shipper_contact_number',
        'shipper_address',
        'shipper_lat',
        'shipper_lng',
        'drop_off_city_id',
        'receiver_name',
        'receiver_contact_number',
        'receiver_address',
        'receiver_lat',
        'receiver_lng',
        'product',
        'product_id',
        'type_of_packing',
        'number_of_packets',
        'detailed_description',
        'weight',
        'weight_unit',
        'volume',
        'volume_unit',
        'departure_date',
        'departure_time',
        'bid_offer',
        'comments_and_wishes',
        'terms_agreed',
        'shipment_requestor'
    ];
}
