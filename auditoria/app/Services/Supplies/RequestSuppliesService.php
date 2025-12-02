<?php

namespace App\Services\Supplies;

use App\Interfaces\MailServiceInterface;
use App\Models\PersonalDetails;
use App\Models\RequestSupply;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class RequestSuppliesService
{
    protected $fileStorageService;
    private $mailService;
    public function __construct(FileStorageService $fileStorageService, MailServiceInterface $mailService)
    {
        $this->fileStorageService = $fileStorageService;
        $this->mailService = $mailService;
    }
    public function get(array $with = []): Collection
    {
        return RequestSupply::orderBy('id', 'DESC')->get();
    }
    public function getToManager(array $with = []): ?Collection
    {
        return RequestSupply::whereIn('status', ['authorized','processed'])->orderBy('id', 'DESC')->get();
    }
    public function getByID(int $id, array $with = []): ?Model
    {
        return RequestSupply::find($id);
    }
    public function getByUser(int $id, array $with = []): ?Collection
    {
        return RequestSupply::where('user_id', $id)->orderBy('id', 'DESC')->get();
    }
    public function getBySupervision(int $id, array $with = []): ?Collection
    {
        $userIds = User::where('manager_id', $id)->pluck('id');
        return RequestSupply::whereIn('user_id', $userIds)->orderBy('id', 'DESC')->get();
    }

    public function create(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'description' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'user_id.required' => 'El ID de usuario es obligatorio.',
            'user_id.integer' => 'El ID de usuario debe ser un número entero.',
            'user_id.exists' => 'El ID de usuario seleccionado no es válido.',
            'description.required' => 'La descripción de la solicitud es obligatoria.',
            'description.string' => 'La descripción de la solicitud debe ser una cadena de texto.',
            'description.max' => 'La descripción de la solicitud no puede tener más de :max caracteres.',
            'description.min' => 'La descripción de la solicitud debe tener más de :min caracteres.',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return DB::transaction(function () use (&$data) {
            $data = RequestSupply::create($data);
            return $data;
        });
    }
    public function update(array $data, int $id): Model
    {
        return DB::transaction(function () use (&$data, $id) {
            $record = RequestSupply::find($id);
            if ($record) {
                $record->update($data);
            }
            return $record;
        });
    }
    public function updateAdmin(array $data, int $id): Model
    {
        $validator = Validator::make($data, [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',  Rule::unique('users', 'email')->ignore($id)],
            'code_job' => ['required', 'string', 'max:20'],
            'manager_id' => ['required', 'integer', 'exists:users,id'],
            'ethnic_group' => ['required', 'string', 'max:255'],
            'profile_picture_url' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ], [
            'user_id.required' => 'El ID de usuario es obligatorio.',
            'user_id.integer' => 'El ID de usuario debe ser un número entero.',
            'user_id.exists' => 'El ID de usuario seleccionado no es válido.',
            'manager_id.required' => 'Seleccionar a un jefe o encargado es obligatorio.',
            'manager_id.integer' => 'El ID de usuario debe ser un número entero.',
            'manager_id.exists' => 'El ID de jefe seleccionado no es válido.',
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'last_name.required' => 'El apellido es obligatoria.',
            'last_name.string' => 'El apellido debe ser una cadena de texto.',
            'last_name.max' => 'El apellido no puede tener más de :max caracteres.',
            'code_job.required' => 'El código de trabajo es obligatorio.',
            'code_job.string' => 'El código de trabajo debe ser una cadena de texto.',
            'code_job.max' => 'El código de trabajo no puede tener más de :max caracteres.',
            'ethnic_group.required' => 'El genero es obligatorio.',
            'ethnic_group.string' => 'El genero debe ser una cadena de texto.',
            'ethnic_group.max' => 'El genero no puede tener más de :max caracteres.',
            'profile_picture_url.image' => 'El archivo debe ser una imagen válida.',
            'profile_picture_url.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg o webp.',
            'profile_picture_url.max' => 'La imagen no debe exceder los :max kilobytes de tamaño.',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return DB::transaction(function () use (&$data, $id) {
            $user = User::find($id);
            if ($user) {
                $user->update([
                    'name'      => $data['name'] ?? $user->name,
                    'last_name' => $data['last_name'] ?? $user->last_name,
                    'email'     => $data['email'] ?? $user->email,
                    'manager_id'     => $data['manager_id'] ?? $user->manager_id,
                ]);
                $record = PersonalDetails::find($id);
                if (isset($data['profile_picture_url'])) {
                    $file = $data['profile_picture_url'];
                    $oldFilePath = $record ? $record->profile_picture_url : null;
                    $newFilePath = $this->fileStorageService->storeFile($file, 'images', $oldFilePath);
                    $data['profile_picture_url'] = $newFilePath;
                }
                $otrosDatos = collect($data)->only([
                    'code_job',
                    'ethnic_group',
                    'profile_picture_url'
                ])->toArray();
                $record = PersonalDetails::updateOrCreate(
                    ['user_id' => $id], // Condición para encontrar el registro
                    $otrosDatos          // Datos a actualizar o crear
                );
                return $record;
            } else {
                throw new ModelNotFoundException("Usuario no encontrado");
            }
        });
    }
    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $registro = RequestSupply::findOrFail($id);
                $registro->status = 'deleted';
                $registro->save();
                return true;
            });
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
