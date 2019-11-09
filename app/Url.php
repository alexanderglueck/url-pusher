<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
