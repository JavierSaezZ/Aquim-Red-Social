<?php

namespace App\Console\Commands;

use App\Support\ProfilePhotoOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSmallProfilePhotos extends Command
{
    protected $signature = 'profile-photos:generate-small';

    protected $description =
        'Genera avatares 128x128 para las fotos de perfil existentes';

    public function handle(): int
    {
        if (!extension_loaded('gd')) {
            $this->error(
                'La extensión PHP GD no está instalada o activada.'
            );

            return self::FAILURE;
        }

        $diskName = config(
            'jetstream.profile_photo_disk',
            'public'
        );

        $disk = Storage::disk($diskName);
        $files = $disk->allFiles('profile-photos');

        $created = 0;
        $failed = 0;

        foreach ($files as $file) {
            if (str_starts_with(
                $file,
                'profile-photos/small/'
            )) {
                continue;
            }

            if (ProfilePhotoOptimizer::createSmall($file)) {
                $created++;
                $this->line('OK  ' . $file);
            } else {
                $failed++;
                $this->warn('NO  ' . $file);
            }
        }

        $this->newLine();
        $this->info(
            "Terminadas: {$created} | Fallidas: {$failed}"
        );

        return $failed === 0
            ? self::SUCCESS
            : self::FAILURE;
    }
}
