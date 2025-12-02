<?php

namespace App\Services;

use App\Interfaces\MailServiceInterface;
use App\Models\PersonalDetails;
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
use Spatie\Permission\Models\Role;

class RoleService
{
    protected $fileStorageService;
    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }
    public function get(): Collection
    {
        return Role::orderBy('id', 'ASC')->get();
    }
    public function getByID(int $id, array $with): ?User
    {
        return User::with($with)->findOrFail($id);
    }
    public function create(array $data)
    {
        $validator = Validator::make($data, [
            // 'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'code_job' => ['required', 'string', 'max:20'],
            'ethnic_group' => ['required', 'string', 'max:255'],
            'profile_picture_url' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'manager_id' => ['required', 'integer', 'exists:users,id'],
        ], [
            // 'user_id.required' => 'El ID de usuario es obligatorio.',
            // 'user_id.integer' => 'El ID de usuario debe ser un número entero.',
            // 'user_id.exists' => 'El ID de usuario seleccionado no es válido.',
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
            'ethnic_group.required' => 'La etnia es obligatoria.',
            'ethnic_group.string' => 'La etnia debe ser una cadena de texto.',
            'ethnic_group.max' => 'La etnia no puede tener más de :max caracteres.',
            'profile_picture_url.required' => 'La imagen es obligatoria.',
            'profile_picture_url.image' => 'El archivo debe ser una imagen válida.',
            'profile_picture_url.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg o webp.',
            'profile_picture_url.max' => 'La imagen no debe exceder los :max kilobytes de tamaño.',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return DB::transaction(function () use (&$data) {
            $filePath = null;
            if (isset($data['profile_picture_url'])) {
                $file = $data['profile_picture_url'];
                $filePath = $this->fileStorageService->storeFile($file, 'profile'); // Guarda el archivo usando el servicio
                $data['profile_picture_url'] = $filePath;
            }
            $pasword = "password";
            $user = User::create([
                'name'      => $data['name'],
                'last_name' => $data['last_name'],
                'email'     => $data['email'],
                'manager_id'     => $data['manager_id'],
                'email_verified_at' => now(),
                'password' => Hash::make($pasword),
            ]);
            if ($user) {
                $personalDetails = PersonalDetails::create([
                    'user_id'      => $user->id,
                    'code_job' => $data['code_job'],
                    'ethnic_group'     => $data['ethnic_group'],
                    'profile_picture_url'     => $data['profile_picture_url'],
                ]);
                return [
                    'user' => $user,
                    'personal_details' => $personalDetails,
                ];
            }
            return null;
        });
    }
    public function update(array $data, int $id): Model
    {
        $validator = Validator::make($data, [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'date_of_birth' => ['required', 'date'],
            'phone_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            'email_personal' => ['required', 'string'],
            'nit' => ['required', 'string', 'max:15'],
            'dpi' => ['required', 'string', 'max:13'],
            'gender' => ['required', 'string', 'max:1'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'profile_picture_url' => ['file', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ], [
            'user_id.required' => 'El ID de usuario es obligatorio.',
            'user_id.integer' => 'El ID de usuario debe ser un número entero.',
            'user_id.exists' => 'El ID de usuario seleccionado no es válido.',
            'birthdate.required' => 'La fecha de nacimiento es obligatoria.',
            'birthdate.date' => 'La fecha de nacimiento no es una fecha válida.',
            'phone_number.required' => 'El número de telefono es obligatorio.',
            'phone_number.string' => 'El número de telefono debe ser una cadena de texto.',
            'phone_number.max' => 'El número de telefono no puede tener más de :max caracteres.',
            'address.required' => 'La Dirección es obligatoria.',
            'address.string' => 'La Dirección debe ser una cadena de texto.',
            'address.max' => 'La Dirección no puede tener más de :max caracteres.',
            'nit.required' => 'El NIT es obligatorio.',
            'nit.string' => 'El NIT debe ser una cadena de texto.',
            'nit.max' => 'El NIT no puede tener más de :max caracteres.',
            'dpi.required' => 'El DPI es obligatorio.',
            'dpi.number' => 'El DPI debe ser un número.',
            'dpi.max' => 'El DPI no puede tener más de :max digitos.',
            'gender.required' => 'El genero es obligatorio.',
            'gender.string' => 'El genero debe ser una cadena de texto.',
            'gender.max' => 'El genero no puede tener más de :max caracteres.',
            'city.required' => 'La ciudad es obligatoria.',
            'city.string' => 'La ciudad debe ser una cadena de texto.',
            'city.max' => 'La ciudad no puede tener más de :max caracteres.',
            'country.required' => 'El genero es obligatorio.',
            'country.string' => 'El genero debe ser una cadena de texto.',
            'country.max' => 'El genero no puede tener más de :max caracteres.',
            'profile_picture_url.image' => 'El archivo debe ser una imagen válida.',
            'profile_picture_url.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg o webp.',
            'profile_picture_url.max' => 'La imagen no debe exceder los :max kilobytes de tamaño.',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return DB::transaction(function () use (&$data, $id) {
            $record = PersonalDetails::find($id);
            if (isset($data['profile_picture_url'])) {
                $file = $data['profile_picture_url'];
                $oldFilePath = $record ? $record->profile_picture_url : null;
                $newFilePath = $this->fileStorageService->storeFile($file, 'images', $oldFilePath);
                $data['profile_picture_url'] = $newFilePath;
            }
            $record = PersonalDetails::updateOrCreate(
                ['user_id' => $id], // Condición para encontrar el registro
                $data          // Datos a actualizar o crear
            );
            return $record;
        });
    }
    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $registro = User::findOrFail($id);
                $registro->state = 0;
                $registro->save();

                PersonalDetails::where('user_id', $registro->id)->update(['state' => 0]);

                return true;
            });
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
