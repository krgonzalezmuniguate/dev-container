<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class PdfService
{
    /**
     * Genera un PDF y lo devuelve como stream
     */
    public function stream(string $view, array $data, string $fileName = 'document.pdf')
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->stream($fileName);
    }

    /**
     * Genera un PDF y lo descarga
     */
    public function download(string $view, array $data, string $fileName = 'document.pdf')
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->download($fileName);
    }

    /**
     * Genera un PDF y lo guarda en storage/app/{path}
     */
    public function save(string $view, array $data, string $path)
    {
        try {
            // Genera el contenido binario del PDF.
            $pdfOutput = Pdf::loadView($view, $data)->output();

            // Verifica si el contenido del PDF está vacío.
            if (empty($pdfOutput)) {
                Log::error("El contenido del PDF está vacío para la vista: " . $view);
                return 'Error: El PDF generado está vacío.';
            }

            // Asegura que el directorio de destino existe.
            $directory = dirname($path);
            if ($directory !== '.' && !Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }

            // Guarda el contenido binario en el archivo.
            Storage::put($path, $pdfOutput);

            // Confirma si el archivo fue guardado correctamente.
            if (Storage::exists($path)) {
                Log::info("PDF guardado con éxito en: " . $path);
                return $path;
            } else {
                Log::error("Fallo al guardar el PDF en: " . $path);
                return 'Error: Fallo al guardar el archivo PDF.';
            }

        } catch (\Exception $e) {
            Log::error("Excepción al guardar el PDF: " . $e->getMessage());
            return 'Error: ' . $e->getMessage();
        }
    }


}
