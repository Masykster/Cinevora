<?php
 
namespace App\Helpers;
 
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
 
class ImageHelper
{
    /**
     * Convert an uploaded image to WebP format, optionally resizing it, and save to the specified disk.
     */
    public static function storeAsWebp(
        TemporaryUploadedFile $file,
        string $directory,
        string $disk = 'supabase',
        ?int $maxWidth = null,
        ?int $maxHeight = null
    ): string {
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
 
        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);
 
        // Perform in-memory resizing if dimensions exceed limits
        if (($maxWidth && $width > $maxWidth) || ($maxHeight && $height > $maxHeight)) {
            $ratio = $width / $height;
            $newWidth = $width;
            $newHeight = $height;
 
            if ($maxWidth && $newWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) ($newWidth / $ratio);
            }
 
            if ($maxHeight && $newHeight > $maxHeight) {
                $newHeight = $maxHeight;
                $newWidth = (int) ($newHeight * $ratio);
            }
 
            $scaled = imagescale($image, $newWidth, $newHeight);
            if ($scaled !== false) {
                imagedestroy($image);
                $image = $scaled;
            }
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
 
    /**
     * Get responsive src, srcset, and sizes attributes for an image.
     */
    public static function getResponsiveAttributes(?string $url, string $type = 'poster'): array
    {
        if (!$url) {
            return [
                'src' => asset('images/placeholder-movie.jpg'),
                'srcset' => '',
                'sizes' => '',
            ];
        }
 
        $srcset = [];
        $sizes = '';
        $fallbackSrc = $url;
 
        // 1. TMDB Image URL handling
        if (str_contains($url, 'image.tmdb.org')) {
            if (preg_match('/(https:\/\/image\.tmdb\.org\/t\/p\/)[^\/]+(\/.*)/', $url, $matches)) {
                $base = $matches[1];
                $path = $matches[2];
 
                if ($type === 'poster') {
                    $widths = [185, 342, 500, 780];
                    foreach ($widths as $w) {
                        $srcset[] = "{$base}w{$w}{$path} {$w}w";
                    }
                    $fallbackSrc = "{$base}w500{$path}";
                    $sizes = '(max-width: 640px) 150px, (max-width: 768px) 180px, 300px';
                } elseif ($type === 'banner') {
                    $widths = [300, 780, 1280];
                    foreach ($widths as $w) {
                        $srcset[] = "{$base}w{$w}{$path} {$w}w";
                    }
                    $fallbackSrc = "{$base}w1280{$path}";
                    $sizes = '100vw';
                }
            }
        }
        // 2. Supabase Storage URL handling
        elseif (str_contains($url, '/object/public/')) {
            $renderBase = str_replace('/object/public/', '/render/image/public/', $url);
 
            if ($type === 'poster') {
                $widths = [150, 300, 400];
                foreach ($widths as $w) {
                    $srcset[] = "{$renderBase}?width={$w}&quality=80 {$w}w";
                }
                $fallbackSrc = "{$renderBase}?width=400&quality=80";
                $sizes = '(max-width: 640px) 150px, (max-width: 768px) 180px, 300px';
            } elseif ($type === 'banner') {
                $widths = [480, 800, 1200];
                foreach ($widths as $w) {
                    $srcset[] = "{$renderBase}?width={$w}&quality=80 {$w}w";
                }
                $fallbackSrc = "{$renderBase}?width=1200&quality=80";
                $sizes = '100vw';
            } elseif ($type === 'promo') {
                $widths = [320, 480, 600];
                foreach ($widths as $w) {
                    $srcset[] = "{$renderBase}?width={$w}&quality=80 {$w}w";
                }
                $fallbackSrc = "{$renderBase}?width=600&quality=80";
                $sizes = '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw';
            } elseif ($type === 'product') {
                $widths = [150, 300, 400];
                foreach ($widths as $w) {
                    $srcset[] = "{$renderBase}?width={$w}&quality=80 {$w}w";
                }
                $fallbackSrc = "{$renderBase}?width=400&quality=80";
                $sizes = '(max-width: 640px) 50vw, 200px';
            }
        }
 
        return [
            'src' => $fallbackSrc,
            'srcset' => !empty($srcset) ? implode(', ', $srcset) : '',
            'sizes' => $sizes,
        ];
    }
}
