<?php

namespace App\Http\Controllers\Supplies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\MailServiceInterface;
use App\Responders\IndexResponder;
use App\Responders\StoreResponder;
use App\Responders\UpdateResponder;
use App\Responders\DeleteResponder;
use App\Responders\ShowResponder;
use App\Services\Supplies\SupplyService;
use App\Services\QrCodeService;
use App\Services\PdfService;
use App\Events\EmailEvent;
use App\Http\Resources\Supplies\RequestsuppliesResource;
use App\Http\Resources\Supplies\SuppliesResource;
use App\Models\RequestSupply;
use App\Models\User;

class SupplyController extends Controller
{
    protected $Service;
    protected $IndexResponder;
    protected $StoreResponder;
    protected $UpdateResponder;
    protected $DeleteResponder;
    protected $ShowResponder;
    public $mailService;
    protected QrCodeService $qrService;
    protected PdfService $pdfService;
    public function __construct(PdfService $pdfService, QrCodeService $qrService, SupplyService $Service, IndexResponder $IndexResponder, StoreResponder $StoreResponder, UpdateResponder $UpdateResponder, DeleteResponder $DeleteResponder, ShowResponder $ShowResponder, MailServiceInterface $mailService)
    {
        $this->Service = $Service;
        $this->IndexResponder = $IndexResponder;
        $this->StoreResponder = $StoreResponder;
        $this->UpdateResponder = $UpdateResponder;
        $this->DeleteResponder = $DeleteResponder;
        $this->ShowResponder = $ShowResponder;
        $this->mailService = $mailService;
        $this->qrService = $qrService;
        $this->pdfService = $pdfService;
    }
    public function index()
    {
        try {
            $items = $this->Service->get();
            return $this->IndexResponder->respond($items, fn($data) => SuppliesResource::collection($data));
        } catch (\Throwable $th) {
            return $this->IndexResponder->respondWithError($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $registro = $this->Service->create($data);
            return $this->StoreResponder->respond($registro);
        } catch (\Throwable $th) {
            return $this->StoreResponder->respondWithError($th);
        }
    }
    public function show(string $id)
    {
        try {
            $record = $this->Service->getByID($id, ['category', 'measures', 'provider', 'movements']);
            return $this->ShowResponder->respond($record, fn($data) => new SuppliesResource($data));
        } catch (\Throwable $th) {
            return $this->ShowResponder->respondWithError($th);
        }
    }
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->all();
            $registro = $this->Service->update($data, $id);
            return $this->UpdateResponder->respond($registro);
        } catch (\Throwable $th) {
            return $this->UpdateResponder->respondWithError($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->Service->delete($id);
            if ($deleted) {
                return $this->DeleteResponder->respond($deleted);
            } else {
                return $this->DeleteResponder->respondWithError(
                    new \Exception("No se pudo eliminar el registro."),
                    "No se pudo eliminar el registro.",
                    "warning",
                    "Advertencia",
                    0
                );
            }
        } catch (\Throwable $th) {
            return $this->DeleteResponder->respondWithError($th);
        }
    }
}
