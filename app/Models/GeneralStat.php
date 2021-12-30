<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralStat extends Model
{
    use HasFactory;

    protected $connection = 'stats';

    protected $fillable = [
      'no_of_rfqs',
      'no_of_categories',
      'no_of_active_suppliers'
    ];
}
