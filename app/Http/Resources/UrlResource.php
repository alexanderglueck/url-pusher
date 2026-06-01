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
            'description' => $this->description,
            'image' => $this->image,
            'push_status' => $this->push_status,
            'pushed_at' => $this->pushed_at,
            'is_favorite' => $this->is_favorite,
            'device_id' => $this->device_id,
            'device' => new DeviceResource($this->whenLoaded('device')),
            'created_at' => $this->created_at,
        ];
    }
}
