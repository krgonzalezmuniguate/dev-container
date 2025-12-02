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
use App\Services\QrCodeService;
use App\Services\PdfService;
use App\Services\Supplies\SupplyCategoryService;

class SupplyCategoryController extends Controller
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
    public function __construct(PdfService $pdfService, QrCodeService $qrService, SupplyCategoryService $Service, IndexResponder $IndexResponder, StoreResponder $StoreResponder, UpdateResponder $UpdateResponder, DeleteResponder $DeleteResponder, ShowResponder $ShowResponder, MailServiceInterface $mailService)
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
            return $this->IndexResponder->respond($items, fn($data) => $data);
        } catch (\Throwable $th) {
            return $this->IndexResponder->respondWithError($th);
        }
    }
}
