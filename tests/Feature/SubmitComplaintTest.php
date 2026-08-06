<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubmitComplaintTest extends TestCase
{
    use RefreshDatabase;

    public function test_submitting_a_complaint_creates_a_ticket_from_ai_diagnosis(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode([
                        'perangkat' => 'Asus ROG',
                        'kendala' => 'Mati total',
                        'estimasi_sparepart' => 'Motherboard',
                        'estimasi_biaya' => 500000,
                    ])]],
                ],
            ]),
        ]);

        $response = $this->post('/kirim-aduan', [
            'email' => 'customer@example.com',
            'aduan' => 'Laptop saya mati total setelah kena air.',
        ]);

        $response->assertRedirect('/konsultasi');
        $this->assertDatabaseHas('tickets', [
            'email' => 'customer@example.com',
            'perangkat' => 'Asus ROG',
            'status' => Ticket::STATUS_KONSULTASI,
        ]);
    }

    public function test_submitting_a_complaint_requires_valid_input(): void
    {
        $response = $this->post('/kirim-aduan', [
            'email' => 'not-an-email',
            'aduan' => 'pendek',
        ]);

        $response->assertSessionHasErrors(['email', 'aduan']);
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_ai_service_connection_failure_shows_friendly_error(): void
    {
        Http::fake([
            'api.groq.com/*' => fn () => throw new \Illuminate\Http\Client\ConnectionException('Connection timed out'),
        ]);

        $response = $this->from('/konsultasi')->post('/kirim-aduan', [
            'email' => 'customer@example.com',
            'aduan' => 'Laptop saya mati total setelah kena air.',
        ]);

        $response->assertRedirect('/konsultasi');
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('tickets', 0);
    }
}
