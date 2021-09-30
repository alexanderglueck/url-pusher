<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->devices()->pluck('id')->contains($this->route('device')->id);
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
