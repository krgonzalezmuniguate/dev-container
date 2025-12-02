<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Supply extends Model
{
    public $fillable = [
        'name',
        'description',
        'stock',
        'minimum_stock',
        'maximum_stock',
        'provider_id',
        'supply_category_id',
        'measurement_unit_id',
        'state',
        'expiration_date',
        'type_acquisition',
    ];

    public function measures()
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
    public function category()
    {
        return $this->belongsTo(SupplyCategory::class, 'supply_category_id');
    }
    public function movements()
    {
        return $this->hasMany(SupplyMovement::class, 'supply_id')->orderBy('created_at', 'desc');
    }
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtoupper($value),
        );
    }
}
