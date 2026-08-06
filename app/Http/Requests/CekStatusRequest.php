<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CekStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('kode_booking')) {
            $this->merge([
                'kode_booking' => strtoupper(trim((string) $this->input('kode_booking'))),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'kode_booking' => ['required', 'string', 'regex:/^SRV-\d{4}-[A-Z0-9]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_booking.regex' => 'Format kode booking tidak valid. Contoh: SRV-2026-7XQPKM',
        ];
    }
}
