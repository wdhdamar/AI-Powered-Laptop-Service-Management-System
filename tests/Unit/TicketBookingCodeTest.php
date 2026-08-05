<?php

namespace Tests\Unit;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketBookingCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_booking_code_of_the_year_starts_at_0001(): void
    {
        $code = Ticket::generateBookingCode();

        $this->assertSame('SRV-' . date('Y') . '-0001', $code);
    }

    public function test_booking_code_increments_from_the_last_one(): void
    {
        Ticket::create([
            'perangkat' => 'Asus ROG',
            'kendala' => 'Mati total',
            'urgensi' => 'medium',
            'raw_text' => 'x',
            'status' => Ticket::STATUS_ANTREAN,
            'kode_booking' => 'SRV-' . date('Y') . '-0007',
        ]);

        $code = Ticket::generateBookingCode();

        $this->assertSame('SRV-' . date('Y') . '-0008', $code);
    }
}
