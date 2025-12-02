<?php

namespace App\Http\Controllers\User;

use App\Events\NewUserCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\PersonalDetailsResource;
use App\Interfaces\MailServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Responders\IndexResponder;
use App\Responders\StoreResponder;
use App\Responders\UpdateResponder;
use App\Responders\DeleteResponder;
use App\Responders\ShowResponder;
use App\Services\PersonalDetailsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\EmailEvent;
class PersonalDetailsController extends Controller
{
    protected $Service;
    protected $IndexResponder;
    protected $StoreResponder;
    protected $UpdateResponder;
    protected $DeleteResponder;
    protected $ShowResponder;
    public $mailService;
    public function __construct(PersonalDetailsService $Service, IndexResponder $IndexResponder, StoreResponder $StoreResponder, UpdateResponder $UpdateResponder, DeleteResponder $DeleteResponder, ShowResponder $ShowResponder, MailServiceInterface $mailService)
    {
        $this->Service = $Service;
        $this->IndexResponder = $IndexResponder;
        $this->StoreResponder = $StoreResponder;
        $this->UpdateResponder = $UpdateResponder;
        $this->DeleteResponder = $DeleteResponder;
        $this->ShowResponder = $ShowResponder;
        $this->mailService = $mailService;
    }
    public function index()
    {
        try {
            $items = $this->Service->get(['personal_details', 'roles']);
            return $this->IndexResponder->respond($items, fn($data) => PersonalDetailsResource::collection($data));
        } catch (\Throwable $th) {
            return $this->IndexResponder->respondWithError($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $data = $request->except('profile_picture_url');
            if ($request->hasFile('profile_picture_url')) {
                $data['profile_picture_url'] = $request->file('profile_picture_url');
            }
            $registro = $this->Service->create($data);
            $usuario = $registro['user'];
            $password = "password";
            event(new NewUserCreated((object) $usuario, $password));
            return $this->StoreResponder->respondWithData($registro);
        } catch (\Throwable $th) {
            return $this->StoreResponder->respondWithError($th);
        }
    }
    public function show(string $id)
    {
        try {
            $record = $this->Service->getByID($id, ['personal_details']);
            return $this->ShowResponder->respond($record, fn($data) => new PersonalDetailsResource($data));
        } catch (\Throwable $th) {
            return $this->ShowResponder->respondWithError($th);
        }
    }
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->except('profile_picture_url');
            if ($request->hasFile('profile_picture_url')) {
                $data['profile_picture_url'] = $request->file('profile_picture_url');
            }
            $registro = $this->Service->update($data, $id);
            return $this->UpdateResponder->respond($registro);
        } catch (\Throwable $th) {
            return $this->UpdateResponder->respondWithError($th);
        }
    }
    public function updateAdmin(Request $request, string $id)
    {
        try {
            $data = $request->except('profile_picture_url');
            if ($request->hasFile('profile_picture_url')) {
                $data['profile_picture_url'] = $request->file('profile_picture_url');
            }

            $registro = $this->Service->updateAdmin($data, $id);
            return $this->UpdateResponder->respond($registro);
        } catch (\Throwable $th) {
            return $this->UpdateResponder->respondWithError($th);
        }
    }
    public function destroy(string $id)
    {
        try {
            $deleted = $this->Service->delete($id);

            if ($deleted) {
                return $this->DeleteResponder->respond($deleted);
            } else {
                return $this->DeleteResponder->respondWithError(
                    new \Exception("No se pudo eliminar el usuario."),
                    "No se pudo eliminar el usuario.",
                    "warning",
                    "Advertencia",
                    0
                );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
