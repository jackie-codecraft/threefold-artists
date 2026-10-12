<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'stripe_customer_id',
        'public_recognition_consent',
    ];

    protected function casts(): array
    {
        return [
            'public_recognition_consent' => 'boolean',
        ];
    }

    /**
     * @return Attribute<string|null, string|null>
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: static function (?string $email): ?string {
                $email = $email === null ? '' : mb_strtolower(trim($email));

                return $email === '' ? null : $email;
            },
        );
    }

    /**
     * @return HasMany<Donation, $this>
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * @return HasMany<DonationSupport, $this>
     */
    public function donationSupports(): HasMany
    {
        return $this->hasMany(DonationSupport::class);
    }

    /**
     * @return HasMany<DonorAccessLink, $this>
     */
    public function accessLinks(): HasMany
    {
        return $this->hasMany(DonorAccessLink::class);
    }
}
