<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessContactDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'division',
        'contact_person',
        'designation',
        'location',
        'locality',
        'postal_code',
        'address',
        'cell_no',
        'telephone_no',
        'email',
        'toll_free_no'
    ];
}
