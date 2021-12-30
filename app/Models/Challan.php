<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    use HasFactory;

    protected $fillable = [
      'from',
      'to',
      'challan_date',
      'items_included',
      'no_of_pieces',
      'weight',
      'bilty_no',
      'courier_name',
      'digital_signature',
      'challan_path'
    ];

    public function toUser()
    {
      return $this->belongsTo(User::class, 'to', 'id');
    }

    public function fromUser()
    {
      return $this->belongsTo(User::class, 'from', 'id');
    }
}
