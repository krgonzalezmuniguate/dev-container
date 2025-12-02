<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyMovement extends Model
{
    public $table = "supply_movements";
    public $fillable = [
        'type',
        'quantity',
        'description',
        'supply_id',
        'user_id',
        'request_id'
    ];
    public function supplies()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }
}
