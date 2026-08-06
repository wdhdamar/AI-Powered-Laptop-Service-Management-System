<?php

namespace Tests\Unit;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketBookingCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_code_matches_expected_format(): void
    {
        $code = Ticket::generateBookingCode();

        $this->assertMatchesRegularExpression(
            '/^SRV-' . date('Y') . '-[23456789ABCDEFGHJKMNPQRSTWXYZ]{6}$/',
            $code
        );
    }

    public function test_booking_code_excludes_ambiguous_characters(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $code = Ticket::generateBookingCode();
            $randomSuffix = substr($code, -6);

            foreach (['0', 'O', '1', 'I', 'L', 'U', 'V'] as $ambiguousChar) {
                $this->assertStringNotContainsString($ambiguousChar, $randomSuffix);
            }
        }
    }

    public function test_consecutive_booking_codes_are_not_sequential(): void
    {
        $first = Ticket::generateBookingCode();
        $second = Ticket::generateBookingCode();

        $this->assertNotSame($first, $second);
    }
}
