<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DevicePairing extends Model
{
    use HasFactory;

    /**
     * How long a freshly issued pairing code stays valid.
     */
    public const TTL_MINUTES = 10;

    protected $fillable = [
        'code',
        'expires_at',
        'device_id',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function isClaimed(): bool
    {
        return $this->device_id !== null;
    }

    /**
     * Limit the query to codes that can still be claimed.
     */
    public function scopeClaimable(Builder $query): Builder
    {
        return $query->whereNull('device_id')->where('expires_at', '>', now());
    }

    public static function generateCode(): string
    {
        return Str::random(40);
    }
}
