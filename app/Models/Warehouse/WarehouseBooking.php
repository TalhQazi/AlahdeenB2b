<?php

namespace App\Models\Warehouse;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'warehouse_id',
        'booked_by',
        'item',
        'start_time',
        'end_time',
        'description',
        'booking_status',
        'quantity',
        'unit',
        'type',
        'area',
        'goods_value',
    ];

    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function invoice()
    {
        return $this->hasOne(BookingAgreementTerm::class, 'booking_id', 'id');
    }

}
