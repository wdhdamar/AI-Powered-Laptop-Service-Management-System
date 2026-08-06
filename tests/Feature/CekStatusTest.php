<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CekStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_booking_code_returns_ticket_details(): void
    {
        Ticket::create([
            'perangkat' => 'Asus ROG',
            'kendala' => 'Mati total',
            'urgensi' => 'medium',
            'raw_text' => 'Laptop mati total',
            'status' => Ticket::STATUS_ANTREAN,
            'kode_booking' => 'SRV-2026-7XQPKM',
        ]);

        $response = $this->post('/cek-status', ['kode_booking' => 'SRV-2026-7XQPKM']);

        $response->assertOk();
        $response->assertViewHas('ticket', fn ($ticket) => $ticket->kode_booking === 'SRV-2026-7XQPKM');
    }

    public function test_lowercase_booking_code_is_normalized_and_still_found(): void
    {
        Ticket::create([
            'perangkat' => 'Asus ROG',
            'kendala' => 'Mati total',
            'urgensi' => 'medium',
            'raw_text' => 'Laptop mati total',
            'status' => Ticket::STATUS_ANTREAN,
            'kode_booking' => 'SRV-2026-7XQPKM',
        ]);

        $response = $this->post('/cek-status', ['kode_booking' => 'srv-2026-7xqpkm']);

        $response->assertOk();
        $response->assertViewHas('ticket', fn ($ticket) => $ticket->kode_booking === 'SRV-2026-7XQPKM');
    }

    public function test_invalid_booking_code_shows_error(): void
    {
        $response = $this->post('/cek-status', ['kode_booking' => 'SRV-2026-ZZZZZZ']);

        $response->assertRedirect();
        $response->assertSessionHas('error_cari');
    }

    public function test_malformed_booking_code_fails_validation(): void
    {
        $response = $this->post('/cek-status', ['kode_booking' => 'SRV-2026-0001']);

        $response->assertSessionHasErrors(['kode_booking']);
    }
}
