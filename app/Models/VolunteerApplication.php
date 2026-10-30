<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VolunteerApplication extends Model
{
    public const INTERESTS = [
        'stage_management' => 'Stage Management / Production',
        'sound_lighting' => 'Sound / QLab / Lighting',
        'costumes_props_sets' => 'Costumes / Props / Sets',
        'photo_video_social' => 'Photography / Video / Social Media',
        'marketing_outreach' => 'Marketing / Community Outreach',
        'fundraising_events' => 'Fundraising / Special Events',
        'general_support' => 'General Volunteer Support',
        'other' => 'Other / Not Sure Yet',
    ];

    public const AVAILABILITY = [
        'weekdays' => 'Weekdays',
        'evenings' => 'Evenings',
        'weekends' => 'Weekends',
        'flexible' => 'Flexible / Varies',
    ];

    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'active' => 'Active volunteer',
        'not_a_fit' => 'Not a fit',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'interests',
        'availability',
        'experience',
        'message',
        'contact_consent_at',
        'status',
        'internal_notes',
    ];

    protected $casts = [
        'interests' => 'array',
        'availability' => 'array',
        'contact_consent_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'new',
    ];

    public function firstName(): string
    {
        $name = trim((string) $this->name);

        return $name === '' ? 'there' : Str::before($name, ' ');
    }

    /**
     * @return list<string>
     */
    public function interestLabels(): array
    {
        return self::labelsFor($this->interests ?? [], self::INTERESTS);
    }

    /**
     * @return list<string>
     */
    public function availabilityLabels(): array
    {
        return self::labelsFor($this->availability ?? [], self::AVAILABILITY);
    }

    /**
     * @param  array<int, string>  $keys
     * @param  array<string, string>  $options
     * @return list<string>
     */
    private static function labelsFor(array $keys, array $options): array
    {
        return array_values(array_map(fn (string $key): string => $options[$key] ?? $key, $keys));
    }
}
