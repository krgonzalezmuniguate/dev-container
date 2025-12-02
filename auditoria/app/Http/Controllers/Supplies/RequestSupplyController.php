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
use App\Services\Supplies\RequestSuppliesService;
use App\Services\QrCodeService;
use App\Services\PdfService;
use App\Events\EmailEvent;
use App\Http\Resources\Supplies\RequestsuppliesResource;
use App\Models\RequestSupply;
use App\Models\User;
use App\Services\FileService;
use Carbon\Carbon;

class RequestSupplyController extends Controller
{
    protected $Service;
    protected $IndexResponder;
    protected $StoreResponder;
    protected $UpdateResponder;
    protected $DeleteResponder;
    protected $ShowResponder;
    public $mailService;
    protected $FileService;
    protected QrCodeService $qrService;
    protected PdfService $pdfService;
    public string $appUrl;
    public function __construct(FileService $FileService, PdfService $pdfService, QrCodeService $qrService, RequestSuppliesService $Service, IndexResponder $IndexResponder, StoreResponder $StoreResponder, UpdateResponder $UpdateResponder, DeleteResponder $DeleteResponder, ShowResponder $ShowResponder, MailServiceInterface $mailService)
    {
        $this->Service = $Service;
        $this->FileService = $FileService;
        $this->IndexResponder = $IndexResponder;
        $this->StoreResponder = $StoreResponder;
        $this->UpdateResponder = $UpdateResponder;
        $this->DeleteResponder = $DeleteResponder;
        $this->ShowResponder = $ShowResponder;
        $this->mailService = $mailService;
        $this->qrService = $qrService;
        $this->pdfService = $pdfService;
        $this->appUrl = getenv('FRONTEND_URL');
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
    public function index_to_manager()
    {
        try {
            $record = $this->Service->getToManager();
            return $this->ShowResponder->respond($record, fn($data) => RequestsuppliesResource::collection($data));
        } catch (\Throwable $th) {
            return $this->ShowResponder->respondWithError($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $registro = $this->Service->create($data);
            $user_id = $registro['user_id'];
            $description = $registro['description'];
            $user = User::with('manager')->find($user_id);
            $actionUrl = $this->appUrl . '/suministros/authorization';
            $payload = [
                'to' => $user->manager->email,
                'subject' => 'Se ha solicitado suministros.',
                'header' => '¡Nueva Solicitud de Suministro!',
                'view' => 'emails.generic_mail',
                'data' => [
                    'greeting' => 'Buen día, estimado(a)',
                    'introLines' => [
                        'El usuario: ' . $user['fullname'] . ' ha solicitado lo siguiente',
                        $description,
                        '',
                        'Puede ver los detalles completos de la solicitud en el siguiente enlace:'
                    ],
                    'outroLines' => [
                        ''
                    ],
                    'actionText' => 'Ver Detalles de la solicitud',
                    'actionUrl' => $actionUrl,
                ]
            ];

            event(new EmailEvent($payload));
            return $this->StoreResponder->respondWithData($registro->toArray());
        } catch (\Throwable $th) {
            return $this->StoreResponder->respondWithError($th);
        }
    }
    public function show(string $id)
    {
        try {
            $record = $this->Service->getByID($id);
            return $this->ShowResponder->respond($record, fn($data) => new RequestsuppliesResource($data));
        } catch (\Throwable $th) {
            return $this->ShowResponder->respondWithError($th);
        }
    }
    public function show_by_user(string $id)
    {
        try {
            $record = $this->Service->getByUser($id);
            return $this->ShowResponder->respond($record, fn($data) => RequestsuppliesResource::collection($data));
        } catch (\Throwable $th) {
            return $this->ShowResponder->respondWithError($th);
        }
    }
    public function show_to_chief(string $id)
    {
        try {
            $record = $this->Service->getBySupervision($id);
            return $this->ShowResponder->respond($record, fn($data) => RequestsuppliesResource::collection($data));
        } catch (\Throwable $th) {
            return $this->ShowResponder->respondWithError($th);
        }
    }
    public function update(Request $request, string $id)
    {
        //
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
    public function approbe($id)
    {
        try {
            $solicitud = RequestSupply::findOrFail($id);
            $user = User::with('manager')->findOrFail($solicitud->user_id);
            $qrDataUri      = $this->qrService->generate((string) $user->signature);
            $qrDataUriChief = $this->qrService->generate((string) $user->manager->signature);
            $fileName = "solicitud_{$solicitud->id}.pdf";
            $path = "solicitudes_suministros/{$fileName}";
            $savedPath = $this->pdfService->save('pdf.solicitud', [
                'solicitud' => $solicitud,
                'user'      => $user,
                'jefe'      => $user->manager,
                'qr'        => $qrDataUri,
                'qr_chief'  => $qrDataUriChief,
            ], $path);
            if (strpos($savedPath, 'Error:') === 0) {
                throw new \Exception($savedPath);
            }
            $data = [
                'path_file' => $savedPath,
                'manager_id' => $user->manager->id,
                'status' => 'authorized',
                'date_authorized' => Carbon::now()
            ];
            $registro = $this->Service->update($data, $id);
            $payload = [
                'to' => $user->email,
                'subject' => 'Se ha aprobado su solicitud de suministros.',
                'header' => '¡Aprobación de solicitud de Suministro!',
                'view' => 'emails.generic_mail',
                'data' => [
                    'greeting' => 'Buen día, estimado(a)',
                    'introLines' => [
                        'Su supervisor ' . $user->manager['fullname'] . ' ha aprobado su solicitud de suministros',
                    ],
                    'outroLines' => [
                        ''
                    ],
                    'actionText' => 'Sistema DAI',
                    'actionUrl' => 'http://172.23.53.149/suministros',
                ]
            ];
            $payload_manager = [
                'to' => 'acarreno@muniguate.com',
                'subject' => 'Se ha generado una solicitud de suministros.',
                'header' => '¡Solicitud de Suministro!',
                'view' => 'emails.generic_mail',
                'data' => [
                    'greeting' => 'Buen día, estimado(a)',
                    'introLines' => [
                        'El supervisor ' . $user->manager['fullname'] . ' ha aprobado una solicitud de suministros',
                        'La cual contiene lo siguiente: ',
                        '',
                        $solicitud['description'],
                    ],
                    'outroLines' => [
                        ''
                    ],
                    'actionText' => 'Sistema DAI',
                    'actionUrl' => 'http://172.23.53.149/suministros/manager',
                ]
            ];
            event(new EmailEvent($payload));
            event(new EmailEvent($payload_manager));
            return $this->UpdateResponder->respond($registro);
        } catch (\Exception $e) {
            return $this->UpdateResponder->respondWithError($e);
        }
    }
    public function reject($id, Request $request)
    {
        try {
            $request = $request->all();
            $solicitud = RequestSupply::findOrFail($id);
            $user = User::with('manager')->findOrFail($solicitud->user_id);
            $data = [
                'manager_id' => $user->manager->id,
                'status' => 'rejected',
                'reason_rejection' => $request['reason_reject']
            ];
            $registro = $this->Service->update($data, $id);
            $payload = [
                'to' => $user->email,
                'subject' => 'Se ha rechazado la solicitud de suministros.',
                'header' => '¡Rechazo Solicitud de Suministro!',
                'view' => 'emails.generic_mail',
                'data' => [
                    'greeting' => 'Buen día, estimado(a)',
                    'introLines' => [
                        'Su supervisor ' . $user->manager->fullname . ' ha rechazado su solicitud',
                        'puede generar una nueva solicitud en el siguiente boton'
                    ],
                    'outroLines' => [
                        ''
                    ],
                    'actionText' => 'Sistema DAI',
                    'actionUrl' => 'http://172.23.53.149/suministros',
                ]
            ];
            event(new EmailEvent($payload));
            return $this->UpdateResponder->respond($registro);
        } catch (\Exception $e) {
            return $this->UpdateResponder->respondWithError($e);
        }
    }
    public function download($id)
    {
        $path = "solicitudes_suministros/solicitud_{$id}.pdf"; // incluye "private"
        return $this->FileService->download($path, "Solicitud_{$id}.pdf");
    }
}
