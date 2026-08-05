<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Sukses - MarsTop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f5f7; color: #333333; margin: 0; padding: 20px; }
        .container { max-width: 600px; background-color: #ffffff; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #f97316; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e293b; text-decoration: none; }
        .accent { color: #f97316; }
        .code-box { text-align: center; background-color: #f8fafc; border: 1px dashed #cbd5e1; padding: 15px; margin: 20px 0; border-radius: 6px; }
        .code { font-family: monospace; font-size: 28px; font-weight: bold; color: #1e293b; letter-spacing: 2px; }
        .details-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .details-table td { padding: 8px 0; vertical-align: top; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <a href="#" class="logo">🚀 Mars<span class="accent">Top</span></a>
            <h2>Booking Antrean Servis Berhasil</h2>
        </div>

        <p>Halo Pelanggan MarsTop,</p>
        <p>Terima kasih telah melakukan pemesanan antrean servis secara online. Perangkat Anda telah terdaftar dalam sistem kami dengan informasi sebagai berikut:</p>

        <div class="code-box">
            <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 5px;">KODE BOOKING ANDA</span>
            <span class="code">{{ $ticket->kode_booking }}</span>
        </div>

        <table class="details-table">
            <tr>
                <td width="35%"><strong>Perangkat</strong></td>
                <td>: {{ $ticket->perangkat }}</td>
            </tr>
            <tr>
                <td><strong>Analisa Kendala</strong></td>
                <td>: {{ $ticket->kendala }}</td>
            </tr>
            <tr>
                <td><strong>Estimasi Komponen</strong></td>
                <td>: {{ $ticket->estimasi_sparepart }}</td>
            </tr>
            <tr>
                <td><strong>Estimasi Awal Biaya</strong></td>
                <td>: <span style="color: #16a34a; font-weight: bold;">Rp {{ number_format($ticket->estimasi_biaya, 0, ',', '.') }}</span></td>
            </tr>
        </table>

        <div style="margin-top: 30px; background-color: #fff7ed; border-left: 4px solid #ea580c; padding: 15px; border-radius: 4px;">
            <strong style="color: #ea580c;">Langkah Selanjutnya:</strong>
            <p style="margin: 5px 0 0 0; font-size: 14px;">Silakan bawa perangkat Anda ke toko fisik kami dalam waktu 1x24 jam. Tunjukkan email ini atau Kode Booking di atas kepada teknisi pada saat penyerahan barang.</p>
        </div>

        <div class="footer">
            <p><strong>MarsTop Service Center &copy; 2026</strong></p>
            <p>Ruko MarsTop Square, Tangerang, Indonesia | Jam Operasional: 09:00 - 21:00 WIB</p>
        </div>
    </div>

</body>
</html>