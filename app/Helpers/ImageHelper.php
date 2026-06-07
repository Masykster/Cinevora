<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ImageHelper
{
    /**
     * Convert an uploaded image to WebP format and save to the specified disk.
     */
    public static function storeAsWebp(TemporaryUploadedFile $file, string $directory, string $disk = 'supabase'): string
    {
        $tempPath = $file->getRealPath();

        // Load the image content
        $imageContent = @file_get_contents($tempPath);
        if ($imageContent === false) {
            return $file->store($directory, $disk);
        }

        $image = @imagecreatefromstring($imageContent);
        if (!$image) {
            // Fallback to storing original if GD fails or file is not a valid image
            return $file->store($directory, $disk);
        }

        // Preserve alpha transparency
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Create a temporary WebP file
        $tempWebpPath = tempnam(sys_get_temp_dir(), 'webp') . '.webp';
        
        // Convert and save as WebP with 80% quality
        if (@imagewebp($image, $tempWebpPath, 80)) {
            imagedestroy($image);
            
            $filename = Str::random(40) . '.webp';
            // Upload the temporary webp file to the specified storage disk
            $storedPath = Storage::disk($disk)->putFileAs($directory, new File($tempWebpPath), $filename);
            
            // Delete the local temporary webp file
            @unlink($tempWebpPath);
            
            return $storedPath ?: $file->store($directory, $disk);
        }

        imagedestroy($image);
        return $file->store($directory, $disk);
    }
}
