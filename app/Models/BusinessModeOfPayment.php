<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusinessDetail;
use App\Models\ModeOfPayment;

class BusinessModeOfPayment extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'mode_of_payment_id'];

    public function business() {
        return $this->belongsTo(BusinessDetail::class);
    }

    public function modeOfPayment() {
        return $this->belongsTo(ModeOfPayment::class);
    }
}
