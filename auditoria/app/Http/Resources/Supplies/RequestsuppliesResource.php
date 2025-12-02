<?php

namespace App\Http\Resources\Supplies;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RequestsuppliesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find($this->user_id);
        $manager = User::find($user->manager_id);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id ?? null,
            'status' => $this->status,
            'description' => $this->description,
            'fecha_creacion_format' => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y') : null,
            'user' => $user->smallname,
            'created_at_format' => $this->created_at ? Carbon::parse($this->created_at)->locale('es')->translatedFormat('j \d\e F \d\e Y') : null,
            'created_at' => $this->created_at,
            'reason_rejection' => $this->reason_rejection,
            'manager' => $manager->smallname,
            'date_authorized' => $this->date_authorized,
            'date_authorized_format' => $this->date_authorized ? Carbon::parse($this->date_authorized)->locale('es')->translatedFormat('j \d\e F \d\e Y') : null,
            'path_file' => $this->path_file,
            'state' => $this->status === 'pending'
                ? 'PENDIENTE'
                : ($this->status === 'authorized'
                    ? 'AUTORIZADA'
                    : ($this->status === 'processed'
                        ? 'PROCESADA'
                        : ($this->status === 'deleted'
                            ? 'ELIMINADA'
                            : ($this->status === 'rejected'
                                ? 'RECHAZADA'
                                : 'DESCONOCIDO'))))
        ];
    }
}
