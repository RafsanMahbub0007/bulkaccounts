<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function show(Request $request, string $path)
    {
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $source = storage_path('app/public/' . $path);

        if (!File::exists($source)) {
            $source = public_path('storage/' . $path);
        }

        if (!File::exists($source)) {
            $source = public_path($path);
        }

        if (!File::exists($source)) {
            abort(404);
        }

        $max = (int) $request->query('max', 1920);
        $max = $max > 0 ? min($max, 4096) : 1920;

        $w = $request->query('w');
        $w = $w !== null ? (int) $w : null;
        $w = $w && $w > 0 ? min($w, 4096) : null;

        [$origW, $origH, $origType] = @getimagesize($source) ?: [null, null, null];

        if (!$w && $origW && $origW > $max) {
            $w = $max;
        }

        $format = $this->resolveFormat($request);
        $quality = $this->resolveQuality($request, $format);

        if (!$w && $format === null) {
            return response()->file($source, [
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        }

        $variantPath = $this->variantPath($source, $path, $w, $format, $quality);

        if (!File::exists($variantPath)) {
            $this->generateVariant($source, $variantPath, $w, $format, $quality);
        }

        $mime = $this->mimeForFormat($format) ?? File::mimeType($variantPath) ?? 'application/octet-stream';

        return response()->file($variantPath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function resolveFormat(Request $request): ?string
    {
        $forced = $request->query('fm');
        if (is_string($forced) && $forced !== '') {
            $forced = strtolower($forced);
            if (in_array($forced, ['avif', 'webp', 'jpg', 'jpeg', 'png'], true)) {
                return $forced === 'jpeg' ? 'jpg' : $forced;
            }
        }

        $accept = (string) $request->header('Accept', '');

        if (str_contains($accept, 'image/avif') && function_exists('imageavif')) {
            return 'avif';
        }

        if (str_contains($accept, 'image/webp') && function_exists('imagewebp')) {
            return 'webp';
        }

        return null;
    }

    private function resolveQuality(Request $request, ?string $format): int
    {
        $q = $request->query('q');
        $q = $q !== null ? (int) $q : null;

        if ($q !== null) {
            return max(35, min(95, $q));
        }

        return match ($format) {
            'avif' => 55,
            'webp' => 85,
            'jpg' => 85,
            default => 90,
        };
    }

    private function variantPath(string $source, string $relativePath, ?int $w, ?string $format, int $quality): string
    {
        $sourceMtime = (string) (File::exists($source) ? File::lastModified($source) : 0);
        $key = sha1($relativePath . '|' . ($w ?: 'orig') . '|' . ($format ?: 'orig') . '|' . $quality . '|' . $sourceMtime);

        $ext = $format ?: Str::lower(pathinfo($source, PATHINFO_EXTENSION) ?: 'img');
        $dir = storage_path('app/public/.imgcache/' . substr($key, 0, 2) . '/' . substr($key, 2, 2));

        File::ensureDirectoryExists($dir);

        return $dir . '/' . $key . '.' . $ext;
    }

    private function generateVariant(string $source, string $dest, ?int $targetW, ?string $format, int $quality): void
    {
        $info = @getimagesize($source);
        if (!$info) {
            abort(404);
        }

        [$width, $height, $type] = $info;
        $mime = $info['mime'] ?? null;

        $img = $this->loadGdImage($source, $mime, $type);
        if (!$img) {
            abort(404);
        }

        $targetW = $targetW && $targetW > 0 ? min($targetW, $width) : $width;
        $targetH = (int) round(($height * $targetW) / $width);

        $out = $img;

        if ($targetW !== $width) {
            $out = imagecreatetruecolor($targetW, $targetH);

            if ($this->needsAlpha($mime, $format)) {
                imagealphablending($out, false);
                imagesavealpha($out, true);
                $transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
                imagefilledrectangle($out, 0, 0, $targetW, $targetH, $transparent);
            }

            imagecopyresampled($out, $img, 0, 0, 0, 0, $targetW, $targetH, $width, $height);
            imagedestroy($img);
        }

        $finalFormat = $format ?: $this->defaultFormatFromMime($mime);

        if ($finalFormat === 'jpg') {
            imageinterlace($out, true);
        }

        $this->saveGdImage($out, $dest, $finalFormat, $quality, $mime);
        imagedestroy($out);
    }

    private function loadGdImage(string $source, ?string $mime, int $type)
    {
        return match ($mime ?: $type) {
            'image/jpeg', IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            'image/png', IMAGETYPE_PNG => @imagecreatefrompng($source),
            'image/webp', IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : null,
            'image/avif', IMAGETYPE_AVIF => function_exists('imagecreatefromavif') ? @imagecreatefromavif($source) : null,
            default => null,
        };
    }

    private function saveGdImage($img, string $dest, string $format, int $quality, ?string $sourceMime): void
    {
        $tmp = $dest . '.tmp';

        $success = match ($format) {
            'avif' => function_exists('imageavif') ? @imageavif($img, $tmp, $quality) : false,
            'webp' => function_exists('imagewebp') ? @imagewebp($img, $tmp, $quality) : false,
            'png' => @imagepng($img, $tmp),
            'jpg' => @imagejpeg($img, $tmp, $quality),
            default => false,
        };

        if (!$success) {
            @unlink($tmp);
            abort(500);
        }

        @chmod($tmp, 0644);
        @rename($tmp, $dest);
    }

    private function defaultFormatFromMime(?string $mime): string
    {
        return match ($mime) {
            'image/png' => 'png',
            default => 'jpg',
        };
    }

    private function needsAlpha(?string $mime, ?string $format): bool
    {
        if ($mime === 'image/png') {
            return true;
        }

        return $format === 'webp' || $format === 'avif';
    }

    private function mimeForFormat(?string $format): ?string
    {
        return match ($format) {
            'avif' => 'image/avif',
            'webp' => 'image/webp',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            default => null,
        };
    }
}
