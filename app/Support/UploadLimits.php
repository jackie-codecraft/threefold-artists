<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Single source of truth for artist application upload limits, shared by
 * server-side validation and the browser-side pre-upload checks.
 */
final class UploadLimits
{
    public const PHOTO_KB = 5120;

    public const RESUME_KB = 10240;

    public const SUPPORTING_MEDIA_KB = 20480;

    public const SUPPORTING_MEDIA_MAX_FILES = 5;

    /** Room left in the request for the text fields and multipart overhead. */
    private const REQUEST_HEADROOM_BYTES = 1024 * 1024;

    /**
     * Largest combined upload one submission can carry. Forge sets nginx's
     * client_max_body_size and PHP's post_max_size from the same value, so
     * post_max_size reflects the server limit. Null means unlimited.
     */
    public static function requestBytes(?string $postMaxSize = null): ?int
    {
        $bytes = self::toBytes($postMaxSize ?? (string) ini_get('post_max_size'));

        return $bytes > 0 ? max($bytes - self::REQUEST_HEADROOM_BYTES, 0) : null;
    }

    /**
     * @return array{photo: int, resume: int, media: int, mediaMaxFiles: int, request: int|null}
     */
    public static function forBrowser(): array
    {
        return [
            'photo' => self::PHOTO_KB * 1024,
            'resume' => self::RESUME_KB * 1024,
            'media' => self::SUPPORTING_MEDIA_KB * 1024,
            'mediaMaxFiles' => self::SUPPORTING_MEDIA_MAX_FILES,
            'request' => self::requestBytes(),
        ];
    }

    private static function toBytes(string $value): int
    {
        $value = trim($value);

        if ($value === '' || ! preg_match('/^(\d+)\s*([kmg]?)/i', $value, $matches)) {
            return 0;
        }

        return (int) $matches[1] * match (strtolower($matches[2])) {
            'g' => 1024 ** 3,
            'm' => 1024 ** 2,
            'k' => 1024,
            default => 1,
        };
    }
}
