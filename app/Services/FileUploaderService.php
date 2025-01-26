<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploaderService
{
    public function uploadFileToLocal($file, string $path): string
    {
        $file_type = $file->getClientOriginalExtension();
        $file_name = generateRandomCharacters();
        $file_name = $file_name.'.'.$file_type;
        return Storage::disk('public_uploads')->putFileAs($path, $file, $file_name);
    }
}
