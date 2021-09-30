<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->devices()->pluck('id')->contains($this->input('device_id'));
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url|max:500',
            'device_id' => 'required'
        ];
    }
}
