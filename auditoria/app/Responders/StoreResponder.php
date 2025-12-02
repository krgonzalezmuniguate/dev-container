<?php

namespace App\Responders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StoreResponder
{
    public function respond(Model $model, string $message = "El registro se ha creado exitosamente", string $title = "Exito", int $code = 1): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'type' => "success",
            'code' => $code,
            'title' => $title,
            'data' => $model, // Incluimos los datos del modelo creado
        ], 201); // Código de estado 201 Created
    }
    public function respondWithData(array $data, string $message = "El registro se ha creado exitosamente", string $title = "Exito", int $code = 1): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'type'    => "success",
            'code'    => $code,
            'title'   => $title,
            'data'    => $data,
        ], 201);
    }
    public function respondWithError(Throwable $exception, string $message = "Hubo un error con su solicitud.", string $type = "danger", string $title = "Error", int $code = 0): JsonResponse
    {
        $statusCode = 500; // default

        // Validación
        if ($exception instanceof ValidationException) {
            $statusCode = 422;
            return response()->json([
                'error' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'message' => $message,
                'code' => $code,
                'title' => $title,
                'type' => $type,
                'errors' => $exception->errors(),
            ], $statusCode);
        }

        // Detectar error de clave única en PostgreSQL
        if ($exception instanceof QueryException) {
            $sqlState = $exception->errorInfo[0] ?? null;
            if ($sqlState === '23505') { // Unique violation
                $statusCode = Response::HTTP_CONFLICT; // 409

                $detail = $exception->errorInfo[2] ?? '';
                $field = null;
                $value = null;

                // Buscar patrón: Key (campo)=(valor)
                if (preg_match('/Key \((.*?)\)=\((.*?)\)/', $detail, $matches)) {
                    $field = $matches[1];
                    $value = $matches[2];
                }

                $conflictMessage = $field && $value
                    ? "El valor \"$value\" para el campo \"$field\" ya existe y debe ser único."
                    : "El valor ya existe y debe ser único.";

                return response()->json([
                    'error' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'message' => $conflictMessage,
                    'code' => $code,
                    'title' => 'Conflicto de datos',
                    'type' => $type,
                    'errors' => null,
                ], $statusCode);
            }
        }

        // Otros errores genéricos
        return response()->json([
            'error' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'message' => $message,
            'code' => $code,
            'title' => $title,
            'type' => $type,
            'errors' => null,
        ], $statusCode);
    }
}
