<!DOCTYPE html>
<html>
<head>
    <title>Servis Selesai</title>
</head>
<body style="font-family: sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f97316; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h2 style="margin: 0;">Kabar Baik! Perangkat Anda Selesai 🎉</h2>
    </div>
    
    <div style="padding: 20px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <p>Halo Pelanggan MarsTop,</p>
        <p>Perangkat Anda telah selesai diperbaiki oleh tim teknisi kami. Berikut adalah rincian detail pengambilan:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #eee;">Kode Booking:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-family: monospace; color: #f97316;"><strong>{{ $ticket->kode_booking }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #eee;">Perangkat:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $ticket->perangkat }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #eee;">Biaya Final:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; color: #16a34a;">Rp {{ number_format($ticket->biaya_final, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <p>Silakan datang ke toko dengan menunjukkan **Kode Booking** di atas ke kasir untuk melakukan pengambilan perangkat dan pelunasan.</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #666; text-align: center;">MarsTop Service Center - Solusi Cepat Perbaikan Gadget Anda</p>
    </div>
</body>
</html>