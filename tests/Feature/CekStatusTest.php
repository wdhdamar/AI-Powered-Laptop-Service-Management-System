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
            'kode_booking' => 'SRV-2026-0001',
        ]);

        $response = $this->post('/cek-status', ['kode_booking' => 'SRV-2026-0001']);

        $response->assertOk();
        $response->assertViewHas('ticket', fn ($ticket) => $ticket->kode_booking === 'SRV-2026-0001');
    }

    public function test_invalid_booking_code_shows_error(): void
    {
        $response = $this->post('/cek-status', ['kode_booking' => 'SRV-2026-9999']);

        $response->assertRedirect();
        $response->assertSessionHas('error_cari');
    }
}
