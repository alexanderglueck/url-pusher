<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // device_id carries the device's public ULID.
        return $this->user()->devices()->pluck('ulid')->contains($this->input('device_id'));
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url|max:500',
            'device_id' => ['required', 'string'],
        ];
    }
}
