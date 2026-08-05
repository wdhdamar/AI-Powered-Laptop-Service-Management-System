<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CekStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_booking' => ['required', 'string'],
        ];
    }
}
