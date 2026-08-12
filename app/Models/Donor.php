<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'stripe_customer_id',
    ];

    /**
     * @return Attribute<string, string>
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: static fn (string $email): string => mb_strtolower(trim($email)),
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
}
