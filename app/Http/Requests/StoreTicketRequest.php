<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'aduan' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'aduan.required' => 'Keluhan wajib diisi.',
            'aduan.min' => 'Ceritakan keluhannya lebih detail ya, minimal 10 karakter.',
            'aduan.max' => 'Keluhan terlalu panjang, maksimal 2000 karakter.',
        ];
    }
}
