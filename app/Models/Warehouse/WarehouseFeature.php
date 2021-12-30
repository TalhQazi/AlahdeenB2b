<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'feature_id',
        'feature'
    ];

    public function featureKey()
    {
        return $this->belongsTo(WarehouseFeatureKey::class, 'feature_id', 'id')->where('status', 1);
    }
}
