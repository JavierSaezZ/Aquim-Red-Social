<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class ProfilePhotoOptimizer
{
    public static function createSmall(string $profilePhotoPath): bool
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        $diskName = config('jetstream.profile_photo_disk', 'public');
        $disk = Storage::disk($diskName);

        if (!$disk->exists($profilePhotoPath)) {
            return false;
        }

        $contents = $disk->get($profilePhotoPath);
        $source = @imagecreatefromstring($contents);

        if (!$source) {
            return false;
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        if ($sourceWidth <= 0 || $sourceHeight <= 0) {
            imagedestroy($source);
            return false;
        }

        $size = 128;
        $destination = imagecreatetruecolor($size, $size);

        if (!$destination) {
            imagedestroy($source);
            return false;
        }

        $imageInfo = @getimagesizefromstring($contents);
        $mime = $imageInfo['mime'] ?? 'image/jpeg';

        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);

            $transparent = imagecolorallocatealpha(
                $destination,
                0,
                0,
                0,
                127
            );

            imagefill($destination, 0, 0, $transparent);
        }

        imagecopyresampled(
            $destination,
            $source,
            0,
            0,
            0,
            0,
            $size,
            $size,
            $sourceWidth,
            $sourceHeight
        );

        ob_start();

        $saved = match ($mime) {
            'image/png' => imagepng($destination, null, 6),
            'image/webp' => function_exists('imagewebp')
                ? imagewebp($destination, null, 82)
                : false,
            default => imagejpeg($destination, null, 82),
        };

        $smallContents = ob_get_clean();

        imagedestroy($source);
        imagedestroy($destination);

        if (!$saved || !$smallContents) {
            return false;
        }

        $smallPath = self::smallPath($profilePhotoPath);

        return (bool) $disk->put(
            $smallPath,
            $smallContents
        );
    }

    public static function deleteSmall(string $profilePhotoPath): void
    {
        $diskName = config('jetstream.profile_photo_disk', 'public');

        Storage::disk($diskName)->delete(
            self::smallPath($profilePhotoPath)
        );
    }

    public static function smallPath(string $profilePhotoPath): string
    {
        return 'profile-photos/small/' . basename($profilePhotoPath);
    }
}
