<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public const STATUS_KONSULTASI = 'konsultasi';
    public const STATUS_ANTREAN = 'antrean';
    public const STATUS_DIKERJAKAN = 'sedang_diperbaiki';
    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'perangkat',
        'kendala',
        'urgensi',
        'raw_text',
        'estimasi_sparepart',
        'estimasi_biaya',
        'status',
        'kode_booking',
        'biaya_final',
        'email',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_biaya' => 'integer',
            'biaya_final' => 'integer',
        ];
    }

    public static function generateBookingCode(): string
    {
        $tahun = date('Y');

        // lockForUpdate() only has an effect inside a DB transaction (as confirmBooking()
        // wraps this in). It serializes concurrent callers so two near-simultaneous
        // confirmations can't compute the same "next" number.
        $bookingTerakhir = static::where('kode_booking', 'LIKE', "SRV-{$tahun}-%")
            ->orderBy('kode_booking', 'desc')
            ->lockForUpdate()
            ->first();

        if ($bookingTerakhir) {
            $nomorTerakhir = (int) substr($bookingTerakhir->kode_booking, -4);
            $nomorUrut = str_pad($nomorTerakhir + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nomorUrut = '0001';
        }

        return "SRV-{$tahun}-{$nomorUrut}";
    }
}
