<?php

namespace App\Services\Supplies;

use App\Interfaces\MailServiceInterface;
use App\Models\PersonalDetails;
use App\Models\RequestSupply;
use App\Models\Supply;
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

class SupplyService
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
        return Supply::with(['category', 'measures', 'provider'])->orderBy('name', 'ASC')->get();
    }
    public function getByID(int $id, array $with = []): ?Model
    {
        return Supply::with($with)->find($id);
    }
    public function create(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:100',
            'stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:1',
            'maximum_stock' => 'required|numeric|min:1|gte:minimum_stock',
            'provider_id' => 'nullable|numeric|exists:providers,id',
            'supply_category_id' => 'required|numeric|exists:supply_categories,id',
            'measurement_unit_id' => 'required|numeric|exists:measurement_units,id',
            'type_acquisition' => 'required|string|in:traslado,compra,donacion,arrendamiento',
            'description' => 'required|string|min:5|max:255',
            'expiration_date' => 'nullable|date'
        ], [
            'name.required' => 'El nombre del suministro es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',

            'stock.required' => 'El stock actual es obligatorio.',
            'stock.numeric' => 'El stock debe ser un valor numérico.',
            'stock.min' => 'El stock debe ser un valor no negativo.',

            'minimum_stock.required' => 'El stock mínimo es obligatorio.',
            'minimum_stock.numeric' => 'El stock mínimo debe ser un valor numérico.',
            'minimum_stock.min' => 'El stock mínimo debe ser al menos 1.',

            'maximum_stock.required' => 'El stock máximo es obligatorio.',
            'maximum_stock.numeric' => 'El stock máximo debe ser un valor numérico.',
            'maximum_stock.min' => 'El stock máximo debe ser al menos 1.',
            'maximum_stock.gte' => 'El stock máximo debe ser mayor o igual al stock mínimo.',

            'provider_id.numeric' => 'El ID del proveedor debe ser un valor numérico.',
            'provider_id.exists' => 'El proveedor seleccionado no es válido.',

            'supply_category_id.required' => 'La categoría del suministro es obligatoria.',
            'supply_category_id.numeric' => 'El ID de la categoría debe ser un valor numérico.',
            'supply_category_id.exists' => 'La categoría seleccionada no es válida.',

            'measurement_unit_id.required' => 'La unidad de medida es obligatoria.',
            'measurement_unit_id.numeric' => 'El ID de la unidad de medida debe ser un valor numérico.',
            'measurement_unit_id.exists' => 'La unidad de medida seleccionada no es válida.',

            'type_acquisition.required' => 'El tipo de adquisición es obligatorio.',
            'type_acquisition.string' => 'El tipo de adquisición debe ser una cadena de texto.',
            'type_acquisition.in' => 'El tipo de adquisición seleccionado no es válido.',

            'description.required' => 'La descripción del suministro es obligatoria.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de :max caracteres.',
            'description.min' => 'La descripción debe tener al menos :min caracteres.',

            'expiration_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return DB::transaction(function () use (&$data) {
            $store = Supply::create($data);

            return $store;
        });
    }
    public function update(array $data, int $id): Model
    {
        return DB::transaction(function () use (&$data, $id) {
            $record = Supply::find($id);
            if ($record) {
                $record->update($data);
            }
            return $record;
        });
    }
    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $registro = Supply::findOrFail($id);
                $registro->state = 0;
                $registro->save();
                return true;
            });
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
