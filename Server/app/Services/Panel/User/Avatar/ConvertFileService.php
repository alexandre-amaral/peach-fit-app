<?php

namespace App\Services\Panel\User\Avatar;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ConvertFileService
{
    /**
     * Converte uma string base64 em UploadedFile
     * 
     * @param string 
     * @return UploadedFile
     */
    
    public function convertBase64ToFile(string $base64String): UploadedFile
    {
        if (preg_match('/^data:(.*);base64,(.*)$/', $base64String, $matches)) {
            $mimeType = $matches[1];
            $base64Content = $matches[2];
        } else {
            $mimeType = 'image/jpeg'; 
            $base64Content = $base64String;
        }

        $data = base64_decode($base64Content);

        $extension = match ($mimeType) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            default => 'jpg',
        };

        
        $tempFileName = 'avatar_' . Str::random(10) . '.' . $extension;
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $tempFileName;

        file_put_contents($tempFilePath, $data);

        return new UploadedFile(
            $tempFilePath,
            $tempFileName,
            $mimeType,
            null,
            true 
        );
    }
}
