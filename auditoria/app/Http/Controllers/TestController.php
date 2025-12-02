<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\EmailEvent;
use App\Models\RequestSupply;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Encoding\Encoding;
use App\Services\QrCodeService;
use App\Interfaces\MailServiceInterface;
use App\Responders\IndexResponder;
use App\Responders\StoreResponder;
use App\Responders\UpdateResponder;
use App\Responders\DeleteResponder;
use App\Responders\ShowResponder;
class TestController extends Controller
{
    protected $IndexResponder;
    protected $StoreResponder;
    protected $UpdateResponder;
    protected $DeleteResponder;
    protected $ShowResponder;
    public $mailService;
    protected QrCodeService $qrService;
    public function __construct(QrCodeService $qrService, IndexResponder $IndexResponder, StoreResponder $StoreResponder, UpdateResponder $UpdateResponder, DeleteResponder $DeleteResponder, ShowResponder $ShowResponder, MailServiceInterface $mailService)
    {
        $this->IndexResponder = $IndexResponder;
        $this->StoreResponder = $StoreResponder;
        $this->UpdateResponder = $UpdateResponder;
        $this->DeleteResponder = $DeleteResponder;
        $this->ShowResponder = $ShowResponder;
        $this->mailService = $mailService;
        $this->qrService = $qrService;

    }
    public function testEmail()
    {
        $payload = [
            'to' => 'kevinricardog23@gmail.com',
            'subject' => 'Tu pedido #1 ha sido confirmado.',
            'header' => '¡Nueva Solicitud de Suministro!',
            'view' => 'emails.generic_mail',
            'data' => [
                'greeting' => 'Hola Kev',
                'introLines' => [
                    'Tu pedido ha sido recibido y está siendo procesado.',
                    'Puedes ver los detalles completos de tu compra en el siguiente enlace:'
                ],
                'outroLines' => [
                    '¡Gracias por tu compra!'
                ],
                'actionText' => 'Ver Detalles de la Orden',
                'actionUrl' => url('/orders/11'),
            ]
        ];

        event(new EmailEvent($payload));
    }
    public function aprobar($id)
    {
        $solicitud = RequestSupply::findOrFail($id);
        $user = User::findOrFail($solicitud->user_id);
        $manager = User::findOrFail($user->manager_id);
        $qrDataUri = $this->qrService->generate((string) $user->signature);
        $qrDataUri_chief = $this->qrService->generate((string) $manager->signature);
        $pdf = Pdf::loadView('pdf.solicitud', [
            'solicitud' => $solicitud,
            'user' => $user,
            'jefe' => $manager,
            'qr' => $qrDataUri,
            'qr_chief' => $qrDataUri_chief,
        ]);
        return $pdf->stream("solicitud_{$solicitud->id}.pdf");
    }
}
