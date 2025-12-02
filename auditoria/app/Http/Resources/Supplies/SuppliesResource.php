<?php

namespace App\Http\Resources\Supplies;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class SuppliesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'stock' => $this->stock,
            'minimum_stock' => $this->minimum_stock,
            'maximum_stock' => $this->maximum_stock,
            'type_acquisition' => $this->type_acquisition,
            'expiration_date' => $this->expiration_date,
            'provider_id' => $this->provider_id,
            'supply_category_id' => $this->supply_category_id,
            'measurement_unit_id' => $this->measurement_unit_id,
            'state' => $this->state,
            'state_format' => $this->state === "1" ? 'ACTIVO' : 'INACTIVO',
            'category' => $this->category?->name,
            'provider' => $this->provider?->name,
            'measures' => $this->measures?->name,
            'created_at_format' => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y') : null,
            'movements' => SupplyMovementResouce::collection($this->movements)
        ];
    }
}
