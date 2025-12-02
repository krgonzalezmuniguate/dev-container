<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class PersonalDetailsResource extends JsonResource
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
        'date_of_birth_format' => $this->personal_details?->date_of_birth ? Carbon::parse($this->personal_details->date_of_birth)->format('d/m/Y'): null,
        'date_of_birth' => $this->personal_details?->date_of_birth,
        'phone_number' => $this->personal_details?->phone_number,
        'address' => $this->personal_details?->address,
        'nit' => $this->personal_details?->nit,
        'dpi' => $this->personal_details?->dpi,
        'gender' => $this->personal_details?->gender,
        'city' => $this->personal_details?->city,
        'country' => $this->personal_details?->country,
        'profile_picture_url' => $this->personal_details?->profile_picture_url,
        'name' => $this->name,
        'last_name' => $this->last_name,
        'email' => $this->email,
        'manager_id' => $this->manager_id,
        'email_personal' => $this->personal_details?->email_personal,
        'department' => $this->personal_details?->department,
        'ethnic_group' => $this->personal_details?->ethnic_group,
        'code_job' => $this->personal_details?->code_job,
        'state' => $this->state == 1 ? 'ACTIVO' : 'INACTIVO',
        'rol' => $this->roles->first()?->name,
        'role_id' => $this->roles->first()?->id,
        ];
    }
}
