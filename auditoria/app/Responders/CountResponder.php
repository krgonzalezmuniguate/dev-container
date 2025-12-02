<?php

namespace App\Responders;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CountResponder
{
    public function respond(int $count, callable $transformCallback = null, ?string $message = null, ?string $type = null, ?int $code = null): JsonResponse
    {
        $message = $message ?? ($count > 0 ? 'Conteo de datos obtenido exitosamente.' : 'No hay datos para contar.');
        $type = $type ?? ($count > 0 ? 'success' : 'info'); // 'info' o 'success'
        $code = $code ?? ($count > 0 ? 1 : 2);

        return response()->json([
            'message' => $message,
            'type' => $type,
            'code' => $code,
            'total' => $count, // Renombrado a 'total' o 'count' para mayor claridad
        ], 200); // Código HTTP 200 por defecto
    }

     public function respondWithError(\Throwable $exception, int $code = 500, string $message = "Hubo un error con su solicitud.", string $type = "danger"): JsonResponse
    {
        $isProduction = app()->environment('production');
        $response = [
            'error' => $exception->getMessage(),
            'message' => $message,
            'code' => $code,
            'type' => $type,
        ];

        if (!$isProduction) {
            $response['line'] = $exception->getLine();
            $response['file'] = $exception->getFile();
            $response['trace'] = $exception->getTrace();
        }

        return response()->json($response, $code);
    }
}
