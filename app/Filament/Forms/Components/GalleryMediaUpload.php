<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class GalleryMediaUpload
{
    public static function make(string $name = 'media'): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make($name)
            ->label('Original media')
            ->helperText('This is the full image or video shown when someone opens the gallery item. It is never cropped.')
            ->collection('media')
            ->disk('public')
            ->acceptedFileTypes([
                'image/*',
                'video/mp4',
                'video/quicktime',
                'video/webm',
            ]);
    }

    public static function thumbnail(string $name = 'thumbnail'): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make($name)
            ->label('Gallery thumbnail crop')
            ->helperText('Optional for photos. Upload the same original photo, then choose the 4:3 crop used on gallery cards. The full image above remains untouched.')
            ->collection('thumbnail')
            ->disk('public')
            ->image()
            ->imageAspectRatio('4:3')
            ->automaticallyOpenImageEditorForAspectRatio()
            ->imageEditor()
            ->imageEditorMode(2);
    }
}
