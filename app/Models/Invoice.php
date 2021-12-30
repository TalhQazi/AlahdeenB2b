<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'invoice_date',
        'payment_due_date',
        'delivery_date',
        'seller_id',
        'seller_details',
        'buyer_id',
        'buyer_details',
        'terms_and_conditions',
        'contact_email',
        'contact_phone',
        'freight_charges',
        'status',
        'is_shared',
        'updated_by'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }
}
