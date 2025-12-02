<?php

namespace App\Responders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class UpdateResponder
{
    /**
     * Responds with a success message after an update operation.
     *
     * @param Model $model The updated model instance.
     * @param string $message The success message.
     * @param string $title The title of the response.
     * @param int $code The response code.
     * @return JsonResponse
     */
    public function respond(Model $model, string $message = "El registro se ha actualizado exitosamente", string $title = "Éxito", int $code = 1): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'type' => "success",
            'code' => $code,
            'title' => $title,
            'data' => $model, // Include the updated model data
        ], 200); // 200 OK for successful updates
    }

    /**
     * Responds with an error message.
     *
     * This method handles exceptions, including validation exceptions,
     * and returns a JSON response with an appropriate error message and status code.
     *
     * @param Throwable $exception The exception that occurred.
     * @param string $message A user-friendly error message.
     * @param string $type The type of error (e.g., "danger", "warning").
     * @param string $title The title of the error response.
     * @param int $code An application-specific error code.
     * @return JsonResponse
     */
    public function respondWithError(Throwable $exception, string $message = "Hubo un error con su solicitud.", string $type = "danger", string $title = "Error", int $code = 0): JsonResponse
    {
        $statusCode = $exception instanceof ValidationException ? 422 : 500;

        return response()->json([
            'error' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'message' => $message,
            'code' => $code,
            'title' => $title,
            'type' => $type,
            'errors' => $exception instanceof ValidationException ? $exception->errors() : null,
        ], $statusCode);
    }
}
