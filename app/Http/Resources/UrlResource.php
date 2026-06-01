<?php

namespace App\Http\Resources;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Url */
class UrlResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
            'device_id' => $this->device_id,
            'created_at' => $this->created_at,
        ];
    }
}
