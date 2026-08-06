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

    // Excludes visually-confusable characters (0/O, 1/I/L, U/V) so codes typed by
    // customers off a screen/receipt are less error-prone.
    private const CODE_CHARSET = '23456789ABCDEFGHJKMNPQRSTWXYZ';

    public static function generateBookingCode(): string
    {
        $tahun = date('Y');

        // A random (rather than sequential) suffix keeps the public booking code from
        // being enumerable — customers can no longer guess other tickets by
        // incrementing the last digits. Collisions are astronomically unlikely
        // (~29^6 combinations per year) and, if one ever happens, are caught by the
        // DB-level unique constraint + retry loop in TicketService::confirmBooking().
        $random = '';
        for ($i = 0; $i < 6; $i++) {
            $random .= self::CODE_CHARSET[random_int(0, strlen(self::CODE_CHARSET) - 1)];
        }

        return "SRV-{$tahun}-{$random}";
    }
}
