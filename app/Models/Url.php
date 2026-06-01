<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PUSH_PENDING = 'pending';

    public const PUSH_SENT = 'sent';

    public const PUSH_FAILED = 'failed';

    protected $fillable = [
        'url',
        'title',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return [
            'is_favorite' => 'boolean',
            'pushed_at' => 'datetime',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
