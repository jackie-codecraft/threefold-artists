<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class GalleryMediaUpload
{
    public static function make(string $name = 'media'): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make($name)
            ->collection('media')
            ->disk('public')
            ->acceptedFileTypes([
                'image/*',
                'video/mp4',
                'video/quicktime',
                'video/webm',
            ]);
    }
}
