<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Responders\IndexResponder;
use App\Responders\StoreResponder;
use App\Responders\UpdateResponder;
use App\Responders\DeleteResponder;
use App\Responders\ShowResponder;
use App\Services\RoleService;
use App\Services\UserService;

class UserController extends Controller
{
    protected $Service;
    protected $IndexResponder;
    protected $StoreResponder;
    protected $UpdateResponder;
    protected $DeleteResponder;
    protected $ShowResponder;
    public $mailService;
    public function __construct(UserService $Service, IndexResponder $IndexResponder, StoreResponder $StoreResponder, UpdateResponder $UpdateResponder, DeleteResponder $DeleteResponder, ShowResponder $ShowResponder)
    {
        $this->Service = $Service;
        $this->IndexResponder = $IndexResponder;
        $this->StoreResponder = $StoreResponder;
        $this->UpdateResponder = $UpdateResponder;
        $this->DeleteResponder = $DeleteResponder;
        $this->ShowResponder = $ShowResponder;
    }
    public function assignRole(Request $request, int $id)
    {
        $request->validate([
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        try {
            $user = $this->Service->assignRole($id, $request->role_id);

            return $this->StoreResponder->respond(
                $user,
                "Rol asignado correctamente."
            );
        } catch (\Throwable $th) {
            return $this->StoreResponder->respondWithError($th);
        }
    }
}
