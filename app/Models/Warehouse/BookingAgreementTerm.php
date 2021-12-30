<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class BookingAgreementTerm extends Model implements Buyable
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'item',
        'description',
        'start_time',
        'end_time',
        'type',
        'quantity',
        'unit',
        'area',
        'goods_value',
        'price',
        'user_terms',
        'owner_terms',
        'created_by',
        'creator_role',
        'commission_percentage',
        'commission_paid',
        'tax_percentage',
        'tax_amount',
        'status',
        'total_paid_to_owner',
        'requestor_payment_status',
        'owner_paid_status'
    ];

    public $payment_status = [
        'pending',
        'paid',
        'cancelled',
        'refunded',
        'received'
    ];


    public function payments()
    {
        return $this->morphToMany(Payment::class, 'payment');
    }

    public function booking()
    {
        return $this->belongsTo(WarehouseBooking::class, 'booking_id', 'id');
    }

    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        return $this->description;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }

    /**
     * Get the weight of the Buyable item.
     *
     * @return float
     */
    public function getBuyableWeight($options = null)
    {
        return 0;
    }
}
