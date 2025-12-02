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
use App\Services\Supplies\SupplyMovementService;
use App\Services\Supplies\RequestSuppliesService;
use App\Services\QrCodeService;
use App\Services\PdfService;
use App\Events\EmailEvent;
use App\Http\Resources\Supplies\RequestsuppliesResource;
use App\Models\RequestSupply;
use App\Models\User;

class SupplyMovementController extends Controller
{
    protected $Service;
    protected $RequestService;
    protected $IndexResponder;
    protected $StoreResponder;
    protected $UpdateResponder;
    protected $DeleteResponder;
    protected $ShowResponder;
    protected $mailService;
    protected QrCodeService $qrService;
    protected PdfService $pdfService;
    public function __construct(PdfService $pdfService, QrCodeService $qrService, SupplyMovementService $Service, RequestSuppliesService $RequestService, IndexResponder $IndexResponder, StoreResponder $StoreResponder, UpdateResponder $UpdateResponder, DeleteResponder $DeleteResponder, ShowResponder $ShowResponder, MailServiceInterface $mailService)
    {
        $this->Service = $Service;
        $this->RequestService = $RequestService;
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
        //
    }
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['request_id'];
            $registro = $this->Service->create($data);
            $update = [
                'status' => 'processed',
            ];
            if ($registro) {
                $updated = $this->RequestService->update($update, $id);
            }
            return $this->StoreResponder->respond($registro);
        } catch (\Throwable $th) {
            return $this->StoreResponder->respondWithError($th);
        }
    }
    public function show(string $id)
    {
        try {
            $record = $this->Service->getByID($id);
            return $this->ShowResponder->respond($record, fn($data) => $data);
        } catch (\Throwable $th) {
            return $this->ShowResponder->respondWithError($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
