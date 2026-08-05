<?php

namespace Tests\Feature;

use App\Mail\ServisSelesaiMail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TicketStatusUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_technician_can_mark_ticket_as_done_and_customer_gets_emailed(): void
    {
        Mail::fake();

        $technician = User::factory()->create();
        $ticket = Ticket::create([
            'perangkat' => 'Asus ROG',
            'kendala' => 'Mati total',
            'urgensi' => 'medium',
            'raw_text' => 'Laptop mati total',
            'email' => 'customer@example.com',
            'status' => Ticket::STATUS_DIKERJAKAN,
        ]);

        $response = $this->actingAs($technician)->post("/admin/update-status/{$ticket->id}", [
            'status' => Ticket::STATUS_SELESAI,
            'biaya_final' => 450000,
        ]);

        $response->assertRedirect();
        $ticket->refresh();
        $this->assertSame(Ticket::STATUS_SELESAI, $ticket->status);
        $this->assertSame(450000, $ticket->biaya_final);

        Mail::assertQueued(ServisSelesaiMail::class, fn ($mail) => $mail->ticket->id === $ticket->id);
    }

    public function test_guest_cannot_update_ticket_status(): void
    {
        $ticket = Ticket::create([
            'perangkat' => 'Asus ROG',
            'kendala' => 'Mati total',
            'urgensi' => 'medium',
            'raw_text' => 'Laptop mati total',
            'status' => Ticket::STATUS_DIKERJAKAN,
        ]);

        $response = $this->post("/admin/update-status/{$ticket->id}", [
            'status' => Ticket::STATUS_SELESAI,
            'biaya_final' => 450000,
        ]);

        $response->assertRedirect('/admin/login');
    }
}
