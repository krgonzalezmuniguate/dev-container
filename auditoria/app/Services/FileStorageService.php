<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    public function storeFile(UploadedFile $file, string $subdirectory): string
    {
        return $file->store($subdirectory, 'public');
    }
    public function deleteFile(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($path);
    }
}
