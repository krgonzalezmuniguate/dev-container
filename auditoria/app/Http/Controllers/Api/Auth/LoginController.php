<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Login y generación de JWT.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'Por favor, introduce un email válido.',
            'password.required' => 'El campo contraseña es obligatorio.',
        ]);

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                throw ValidationException::withMessages([
                    'error' => ['Credenciales inválidas.'],
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No se pudo crear el token.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Obtener el usuario autenticado.
     */
    public function me()
    {
        try {
            // Intenta autenticar al usuario usando el token.
            // Esto es más explícito que Auth::user() si siempre usas JWT.
            $user = JWTAuth::parseToken()->authenticate();

            // Si el usuario no se encuentra (raro si el token es válido), o si el token es nulo.
            if (!$user) {
                return response()->json(['message' => 'Usuario no encontrado o token inválido.'], 404);
            }

            // --- Lógica para obtener y formatear las aplicaciones/módulos ---
            $formattedAppsData = []; // Esta será la estructura final deseada
            $allPermissions = $user->getAllPermissions();

            $modulesAndApps = [];

            foreach ($allPermissions as $permission) {
                // Busca la Application usando el 'name' (slug) del permiso
                $application = Application::where('slug', $permission->name)
                    ->with('menu') // Asumimos que 'menu' es la relación con tu modelo Module
                    ->first();

                if ($application) {
                    $module = $application->menu; // Obtenemos el objeto Module directamente

                    if ($module) {
                        // Si el módulo no ha sido agregado al array temporal, inicialízalo
                        if (!isset($modulesAndApps[$module->id])) {
                            $modulesAndApps[$module->id] = [
                                'id' => $module->id,
                                'title' => $module->name, // Nombre visual del módulo
                                'description' => $module->description ?? '',
                                'icon' => $module->icon ?? null,
                                'color' => $module->color ?? '#000000',
                                'gradient' => $module->gradient ?? null,
                                'url' => $module->url ?? null, // Si el módulo principal tiene URL directa
                                'subApps' => [], // Aquí irán las aplicaciones de este módulo
                            ];
                        }

                        // Añadir la aplicación actual a la lista de subApps de su módulo
                        $modulesAndApps[$module->id]['subApps'][] = [
                            'id' => $application->id,
                            'title' => $application->name, // Nombre visual de la aplicación
                            'description' => $application->description ?? '',
                            'icon' => $application->icon ?? null,
                            'color' => $application->color ?? '#FFFFFF',
                            'url' => $application->url, // URL de la aplicación
                        ];
                    }
                }
            }
            // Convierte el array asociativo (agrupado por id de módulo) a un array indexado
            $formattedAppsData = array_values($modulesAndApps);
            // --- Fin de la lógica para obtener y formatear las aplicaciones/módulos ---

            // Obtener el nombre del primer rol o todos los roles
            $userRoles = $user->roles->isNotEmpty() ? $user->roles[0]->name : null;
            // Si quieres todos los roles en un array:
            // $userRoles = $user->roles->pluck('name')->toArray();

            return response()->json([
                'user' => $user->only('id', 'fullname', 'last_name', 'smallname', 'email', 'image'),
                'roles' => $userRoles,
                'apps' => $formattedAppsData, // ¡Aquí está la estructura deseada!
                'permissions' => $allPermissions,
            ]);
        } catch (TokenExpiredException $e) {
            // Error específico para tokens expirados
            return response()->json(['error' => 'Token expirado.', 'details' => 'Su sesión ha caducado, por favor inicie sesión nuevamente.'], 401);
        } catch (TokenInvalidException $e) {
            // Error específico para tokens inválidos (malformados, modificados, etc.)
            return response()->json(['error' => 'Token inválido.', 'details' => 'El token de acceso no es válido.'], 401);
        } catch (JWTException $e) {
            // Otros errores relacionados con JWT (token no proporcionado, etc.)
            return response()->json(['error' => 'Error de autenticación.', 'details' => 'No se pudo procesar el token.'], 401);
        } catch (\Exception $e) {
            // Captura cualquier otra excepción inesperada
            return response()->json(['error' => 'Error interno del servidor.', 'details' => $e->getMessage()], 500);
        }
    }
    /**
     * Logout: invalidar el token.
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logout exitoso.']);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo cerrar sesión.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refrescar el token JWT.
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo refrescar el token.',
                'details' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Estructura estándar de respuesta con token.
     */
    protected function respondWithToken($token)
    {
        $user = JWTAuth::setToken($token)->toUser();
        $formattedAppsData = [];
        if ($user) {
            // 1. Obtener todos los permisos del usuario.
            $allPermissions = $user->getAllPermissions();

            // 2. Crear un array temporal para agrupar las aplicaciones por módulo
            $modulesAndApps = [];

            foreach ($allPermissions as $permission) {
                // Buscar la Application usando el 'name' (slug) del permiso
                // Cargamos también la relación 'menu' (tu módulo)
                $application = Application::where('slug', $permission->name)
                    ->with('menu') // Asumimos que 'menu' es la relación con tu modelo Module
                    ->first();

                if ($application) {
                    $module = $application->menu; // Obtenemos el objeto Module directamente

                    if ($module) {
                        // Si el módulo no ha sido agregado al array temporal, inicialízalo
                        if (!isset($modulesAndApps[$module->id])) {
                            $modulesAndApps[$module->id] = [
                                'id' => $module->id,
                                'title' => $module->name, // Nombre visual del módulo
                                'description' => $module->description ?? '', // Asegura que exista
                                'icon' => $module->icon ?? null,     // Icono del módulo
                                'color' => $module->color ?? '#000000', // Color principal del módulo
                                'gradient' => $module->gradient ?? null, // Gradiente del módulo
                                'url' => $module->url ?? null, // Si el módulo principal tiene URL directa
                                'subApps' => [], // Aquí irán las aplicaciones de este módulo
                            ];
                        }

                        // Añadir la aplicación actual a la lista de subApps de su módulo
                        $modulesAndApps[$module->id]['subApps'][] = [
                            'id' => $application->id,
                            'title' => $application->name, // Nombre visual de la aplicación
                            'description' => $application->description ?? '', // Asegura que exista
                            'icon' => $application->icon ?? null, // Icono de la aplicación
                            'color' => $application->color ?? '#FFFFFF', // Color de la sub-app (si la tienes)
                            'url' => $application->url, // URL de la aplicación
                        ];
                    }
                }
            }

            // Convertir el array asociativo (agrupado por id de módulo) a un array indexado
            $formattedAppsData = array_values($modulesAndApps);
        }
        return response()->json([
            'message' => 'Bienvenido ha ingresado exitosamente.',
            'user' => $user ? $user->only('id', 'fullname', 'last_name', 'smallname', 'email', 'image') : null,
            'roles' => $user && $user->roles->isNotEmpty() ? $user->roles->first()->name : null,
            'apps' => $formattedAppsData,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }
    public function changePassword(Request $request)
    {
        try {
            // Obtener el usuario autenticado
            $user = JWTAuth::parseToken()->authenticate();

            // Si no se encuentra el usuario, algo anda mal con el token
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado o token inválido.'], 401);
            }

            // Validar los campos de entrada
            $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'current_password.required' => 'La contraseña actual es obligatoria.',
                'new_password.required' => 'La nueva contraseña es obligatoria.',
                'new_password.min' => 'La nueva contraseña debe tener al menos :min caracteres.',
                'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            ]);

            // Verificar si la contraseña actual es correcta
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['La contraseña actual es incorrecta.'],
                ]);
            }

            // Actualizar la contraseña del usuario
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Opcional: Invalidar todos los tokens del usuario para forzar un nuevo inicio de sesión
            // Esto es una buena práctica de seguridad después de un cambio de contraseña.
            JWTAuth::invalidate(JWTAuth::parseToken()); // Invalida el token actual
            // Para invalidar todos los tokens (si usas un sistema de listas negras por ID de usuario, por ejemplo),
            // necesitarías una lógica más compleja que vaya más allá de JWTAuth directamente.
            // Con JWTAuth básico, invalidar el token actual es suficiente para forzar un re-login.

            return response()->json(['message' => 'Contraseña actualizada exitosamente. Por favor, inicie sesión de nuevo con su nueva contraseña.']);
        } catch (ValidationException $e) {
            // Capturar errores de validación
            return response()->json(['errors' => $e->errors()], 422);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado.', 'details' => 'Su sesión ha caducado, por favor inicie sesión nuevamente.'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido.', 'details' => 'El token de acceso no es válido.'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Error de autenticación.', 'details' => 'No se pudo procesar el token.'], 401);
        } catch (\Exception $e) {
            // Capturar cualquier otra excepción inesperada
            return response()->json(['error' => 'Error interno del servidor.', 'details' => $e->getMessage()], 500);
        }
    }
}
