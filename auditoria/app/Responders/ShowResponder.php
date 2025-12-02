<?php

namespace App\Responders;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ShowResponder
{
    public function respond(
        $data,
        ?callable $transformCallback = null,
        ?string $message = null,
        ?string $type = null,
        ?int $code = null
    ): JsonResponse {
        // Validar si $data es null o vacío
        $dataFound = false;

        if ($data instanceof \Illuminate\Support\Collection) {
            // Si es una colección, comprueba si no está vacía.
            $dataFound = $data->isNotEmpty();
        } elseif ($data instanceof \Illuminate\Database\Eloquent\Model) {
            // Si es un modelo, comprueba si no es nulo.
            $dataFound = ($data !== null);
        } elseif (is_array($data)) {
            // Si es un arreglo, comprueba si no está vacío.
            $dataFound = !empty($data);
        }

        // Aplicar transformación solo si hay datos y callback definido
        if ($dataFound && $transformCallback) {
            $data = $transformCallback($data);
        }

        // Ajustar mensaje y tipo según si hay datos o no
        $message = $message ?? ($dataFound ? 'Se encontró el dato solicitado' : 'No se encontró el dato consultado');
        $type = $type ?? ($dataFound ? 'success' : 'warning');
        $code = $code ?? ($dataFound ? 1 : 2);

        return response()->json([
            'message' => $message,
            'type' => $type,
            'code' => $code,
            'datos' => $dataFound ? $data : null
        ]);
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
