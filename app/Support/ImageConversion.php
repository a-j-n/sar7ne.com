<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class ImageConversion
{
    public static function toWebp(UploadedFile $file, int $quality = 82, ?int $maxW = null, ?int $maxH = null): array
    {
        $mime = $file->getClientMimeType();
        $image = null;
        $preserveAlpha = true;

        $contents = file_get_contents($file->getRealPath());
        if ($contents === false) {
            throw new \RuntimeException('Failed to read uploaded image');
        }

        switch (true) {
            case str_contains($mime, 'png'):
                $image = imagecreatefrompng($file->getRealPath());
                $preserveAlpha = true;
                break;
            case str_contains($mime, 'jpeg') || str_contains($mime, 'jpg'):
                $image = imagecreatefromjpeg($file->getRealPath());
                $preserveAlpha = false;
                break;
            case str_contains($mime, 'webp'):
                // Already webp; return original stream
                return [
                    'contents' => $contents,
                    'filename' => pathinfo($file->hashName(), PATHINFO_FILENAME).'.webp',
                    'mime' => 'image/webp',
                ];
            default:
                // Try generic create from string
                $image = imagecreatefromstring($contents);
                $preserveAlpha = true;
        }

        if (! $image) {
            throw new \RuntimeException('Unsupported image type');
        }

        if ($preserveAlpha) {
            imagepalettetotruecolor($image);
            imagesavealpha($image, true);
        }

        // Optional resizing (fit within box, keep aspect)
        if ($maxW || $maxH) {
            $srcW = imagesx($image);
            $srcH = imagesy($image);
            $maxW = $maxW ?? $srcW;
            $maxH = $maxH ?? $srcH;
            $scale = min($maxW / $srcW, $maxH / $srcH, 1.0);
            if ($scale < 1.0) {
                $dstW = (int) floor($srcW * $scale);
                $dstH = (int) floor($srcH * $scale);
                $dst = imagecreatetruecolor($dstW, $dstH);
                if ($preserveAlpha) {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                    imagefilledrectangle($dst, 0, 0, $dstW, $dstH, $transparent);
                }
                imagecopyresampled($dst, $image, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
                imagedestroy($image);
                $image = $dst;
            }
        }

        ob_start();
        if (! imagewebp($image, null, $quality)) {
            imagedestroy($image);
            ob_end_clean();
            throw new \RuntimeException('Failed to convert image to WebP');
        }
        $webpData = ob_get_clean();
        imagedestroy($image);

        return [
            'contents' => $webpData,
            'filename' => pathinfo($file->hashName(), PATHINFO_FILENAME).'.webp',
            'mime' => 'image/webp',
        ];
    }
}
