<?php

namespace Tests\Feature;

use App\Mail\BookingSuksesMail;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirming_booking_generates_code_and_sends_email(): void
    {
        Mail::fake();

        $ticket = Ticket::create([
            'perangkat' => 'Asus ROG',
            'kendala' => 'Mati total',
            'urgensi' => 'medium',
            'raw_text' => 'Laptop mati total',
            'email' => 'customer@example.com',
            'status' => Ticket::STATUS_KONSULTASI,
        ]);

        $response = $this->post("/konfirmasi-booking/{$ticket->id}");

        $response->assertRedirect('/konsultasi');

        $ticket->refresh();
        $this->assertSame(Ticket::STATUS_ANTREAN, $ticket->status);
        $this->assertMatchesRegularExpression('/^SRV-\d{4}-[A-Z0-9]{6}$/', $ticket->kode_booking);

        Mail::assertQueued(BookingSuksesMail::class, fn ($mail) => $mail->ticket->id === $ticket->id);
    }

    public function test_confirming_an_already_confirmed_booking_is_a_no_op(): void
    {
        Mail::fake();

        $ticket = Ticket::create([
            'perangkat' => 'Asus ROG',
            'kendala' => 'Mati total',
            'urgensi' => 'medium',
            'raw_text' => 'Laptop mati total',
            'email' => 'customer@example.com',
            'status' => Ticket::STATUS_KONSULTASI,
        ]);

        $this->post("/konfirmasi-booking/{$ticket->id}");
        $ticket->refresh();
        $firstCode = $ticket->kode_booking;

        $response = $this->post("/konfirmasi-booking/{$ticket->id}");

        $response->assertRedirect('/konsultasi');
        $ticket->refresh();
        $this->assertSame($firstCode, $ticket->kode_booking);
        $this->assertSame(Ticket::STATUS_ANTREAN, $ticket->status);

        Mail::assertQueued(BookingSuksesMail::class, 1);
    }
}
