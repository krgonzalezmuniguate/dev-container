<?php

namespace App\Responders;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException; // Importar la clase ValidationException
class DeleteResponder
{
    /**
     * Responde a una solicitud de eliminación exitosa (soft delete).
     *
     * @param Model $model El modelo que fue eliminado (soft deleted).
     * @param string $message El mensaje de éxito.
     * @param string $title El título de la respuesta.
     * @param int $code El código de respuesta.
     * @return JsonResponse
     */
    public function respond(bool $respuesta, string $message = "El registro se ha eliminado exitosamente.", string $title = "Éxito", int $code = 1): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'type' => "success",
            'code' => $code,
            // Podrías incluir información sobre el modelo eliminado si es necesario.
            // Por ejemplo: 'data' => $model,
        ], 200); // El código de estado 200 OK es apropiado para un borrado exitoso.
    }

    /**
     * Responde con un mensaje de error.
     *
     * Este método maneja excepciones y devuelve una respuesta JSON con un mensaje
     * de error apropiado y un código de estado.
     *
     * @param Throwable $exception La excepción que ocurrió.
     * @param string $message Un mensaje de error amigable para el usuario.
     * @param string $type El tipo de error (p. ej., "danger", "warning").
     * @param string $title El título de la respuesta de error.
     * @param int $code Un código de error específico de la aplicación.
     * @return JsonResponse
     */
    public function respondWithError(Throwable $exception, string $message = "Hubo un error con su solicitud.", string $type = "danger", string $title = "Error", int $code = 0): JsonResponse
    {
        $statusCode = 500; // Por defecto, usa el código de estado 500 (Error interno del servidor).

        // Si la excepción es una instancia de HttpException, usa su código de estado.
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        }

        return response()->json([
            'error' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'message' => $message,
            'code' => $code,
            'title' => $title,
            'type' => $type,
            'errors' => ($exception instanceof ValidationException) ? $exception->errors() : null, // Para ValidationException // Para ValidationException
        ], $statusCode);
    }
}
