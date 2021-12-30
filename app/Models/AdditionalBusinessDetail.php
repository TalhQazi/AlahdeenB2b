<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalBusinessDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'logo',
        'description',
        'start_day',
        'end_day',
        'start_time',
        'end_time',
        'states',
        'included_cities',
        'excluded_cities',
        'company_id',
        'import_export_no',
        'bank_name',
        'income_tax_number',
        'ntn',
        'no_of_production_units',
        'affiliation_memberships',
        'company_branches',
        'owner_cnic',
        'infrastructure_size',
        'cities_to_trade_with',
        'cities_to_trade_from',
        'shipment_modes',
        'payment_modes',
        'arn_no'
    ];

    public function business() {
        return $this->belongsTo(BusinessDetail::class);
    }
}
