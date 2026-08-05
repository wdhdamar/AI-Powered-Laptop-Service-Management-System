<?php

namespace App\Services;

use App\Mail\BookingSuksesMail;
use App\Mail\ServisSelesaiMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TicketService
{
    public function __construct(private GroqDiagnosisService $groq)
    {
    }

    public function createFromComplaint(string $aduan, string $email): Ticket
    {
        $hasilAi = $this->groq->diagnose($aduan);

        return Ticket::create([
            'perangkat' => $hasilAi['perangkat'],
            'kendala' => $hasilAi['kendala'],
            'urgensi' => 'medium',
            'estimasi_sparepart' => $hasilAi['estimasi_sparepart'],
            'estimasi_biaya' => $hasilAi['estimasi_biaya'],
            'raw_text' => $aduan,
            'email' => $email,
            'status' => Ticket::STATUS_KONSULTASI,
        ]);
    }

    public function confirmBooking(int $id): Ticket
    {
        return DB::transaction(function () use ($id) {
            $ticket = Ticket::findOrFail($id);
            $ticket->update([
                'status' => Ticket::STATUS_ANTREAN,
                'kode_booking' => Ticket::generateBookingCode(),
            ]);

            if ($ticket->email) {
                Mail::to($ticket->email)->send(new BookingSuksesMail($ticket));
            }

            return $ticket;
        });
    }

    public function updateStatus(int $id, string $status, ?int $biayaFinal = null): Ticket
    {
        return DB::transaction(function () use ($id, $status, $biayaFinal) {
            $ticket = Ticket::findOrFail($id);

            $updateData = ['status' => $status];
            if ($status === Ticket::STATUS_SELESAI) {
                $updateData['biaya_final'] = $biayaFinal;
            }
            $ticket->update($updateData);

            if ($status === Ticket::STATUS_SELESAI && !empty($ticket->email)) {
                Mail::to($ticket->email)->send(new ServisSelesaiMail($ticket));
            }

            return $ticket;
        });
    }

    public function findByBookingCode(string $kodeBooking): ?Ticket
    {
        return Ticket::where('kode_booking', $kodeBooking)->first();
    }

    public function dashboardTickets(?string $statusFilter): \Illuminate\Database\Eloquent\Collection
    {
        $query = Ticket::orderBy('created_at', 'desc');

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        return $query->get();
    }

    public function dashboardStats(): array
    {
        $stats = Ticket::selectRaw("
                count(*) filter (where status != 'konsultasi') as semua,
                count(*) filter (where status = 'antrean') as antrean,
                count(*) filter (where status = 'sedang_diperbaiki') as sedang_diperbaiki,
                count(*) filter (where status = 'selesai') as selesai,
                coalesce(sum(biaya_final) filter (where status = 'selesai'), 0) as total_pendapatan
            ")
            ->first();

        return [
            'semua' => (int) $stats->semua,
            'antrean' => (int) $stats->antrean,
            'sedang_diperbaiki' => (int) $stats->sedang_diperbaiki,
            'selesai' => (int) $stats->selesai,
            'total_pendapatan' => (int) $stats->total_pendapatan,
        ];
    }
}
