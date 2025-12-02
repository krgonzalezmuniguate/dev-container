<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileService
{
    public function show(string $path)
    {
        if (!Storage::exists($path)) {
            abort(404, 'El archivo no existe');
        }
        return response()->file(storage_path("app/" . $path));
    }

    public function download(string $path, ?string $filename = null)
{
    $disk = Storage::disk('local'); // disco local = storage/app

    // $path ya incluye la carpeta correcta
    if (!$disk->exists($path)) {
        Log::error("PDFService: archivo NO existe en: " . $disk->path($path));
        abort(404, 'El archivo no existe en: ' . $disk->path($path));
    } else {
        Log::info("PDFService: archivo SI existe");
    }

    return response()->download($disk->path($path), $filename ?? basename($path));
}
    public function publicUrl(string $path): string
    {
        return asset("storage/" . $path);
    }
}
