<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:' . implode(',', [
                Ticket::STATUS_ANTREAN,
                Ticket::STATUS_DIKERJAKAN,
                Ticket::STATUS_SELESAI,
            ])],
            'biaya_final' => ['required_if:status,' . Ticket::STATUS_SELESAI, 'integer', 'min:0'],
        ];
    }
}
