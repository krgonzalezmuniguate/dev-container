<?php

namespace App\Responders;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class IndexResponder
{
    public function respond(Collection $collection, ?callable $transformCallback = null, ?string $message = null, ?string $type = null, ?int $code = null): JsonResponse
    {
        // Si se pasa una callback, la usamos para transformar los datos
        if ($transformCallback) {
            $collection = $transformCallback($collection);
        }

        // Asignar valores predeterminados si no se proporcionan
        $message = $message ?? ($collection->isNotEmpty() ? 'Se encontraron los datos solicitados' : 'No se encontraron los datos consultados');
        $type = $type ?? ($collection->isNotEmpty() ? 'success' : 'warning');
        $code = $code ?? ($collection->isNotEmpty() ? 1 : 2);

        return response()->json([
            'message' => $message,
            'type' => $type,
            'code' => $code,
            'datos' => $collection
        ]);
    }

    public function respondWithError(\Throwable $exception, int $code = 500, string $message = "Hubo un error con su solicitud.", string $type = "danger"): JsonResponse
    {
        // Decidir si enviar más detalles según el entorno
        $isProduction = app()->environment('production');

        // Definir el contenido de la respuesta
        $response = [
            'error' => $exception->getMessage(),
            'message' => $message,
            'code' => $code,
            'type' => $type,
        ];

        // Solo incluir detalles adicionales en entornos de desarrollo
        if (!$isProduction) {
            $response['line'] = $exception->getLine();
            $response['file'] = $exception->getFile();
            $response['trace'] = $exception->getTrace();
        }

        // Respuesta en formato JSON
        return response()->json($response, $code);
    }
}
