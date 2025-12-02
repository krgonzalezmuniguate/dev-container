<?php

namespace App\Http\Resources\Supplies;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class SupplyMovementResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id' => $this->id,
            'description' => $this->description,
            'type' => $this->type,
            'type_format' => $this->type === "1" ? "ENTRADA" : "SALIDA",
            'quantity' => $this->quantity,
            'request_id' => $this->request_id,
            'created_at' => $this->created_at,
             'created_at_format' => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y') : null,

        ];
    }
}
