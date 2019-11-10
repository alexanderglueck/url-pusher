<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithDeviceToken($builder)
    {
        $builder->whereNotNull('device_token');
    }
}
