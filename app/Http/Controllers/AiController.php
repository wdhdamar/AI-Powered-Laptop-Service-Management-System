<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\BookingSuksesMail;
use App\Mail\ServisSelesaiMail;

class AiController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $teksAduanUser = $request->input('aduan');

        // 1. Kirim data ke Groq AI Llama 3.1
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'), 
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.1-8b-instant', 
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Anda adalah AI bot ahli servis laptop dan komputer. Tugas Anda wajib mengekstrak teks keluhan pelanggan menjadi format JSON bersih dengan key wajib berikut: 1. "perangkat" (String: Merk atau tipe laptop/PC), 2. "kendala" (String: Penjelasan singkat kerusakan), 3. "estimasi_sparepart" (String: Nama komponen fisik yang rusak), 4. "estimasi_biaya" (Integer/Angka Saja: Perkiraan harga servis & sparepart dalam Rupiah). Jangan memberikan teks penjelasan apapun di luar JSON!'
                ],
                [
                    'role' => 'user',
                    'content' => $teksAduanUser
                ]
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.0 // Mengunci konsistensi harga AI
        ]);

        $responseData = $response->json();
        if (!isset($responseData['choices'])) { return back()->with('error', 'API Limit atau Error'); }

        $hasilAi = json_decode($responseData['choices'][0]['message']['content'], true);

        // 2. Simpan status awal sebagai 'konsultasi' (Belum masuk antrean toko)
        $idTiket = DB::table('tickets')->insertGetId([
            'perangkat'          => $hasilAi['perangkat'] ?? 'Tidak Terdeteksi',
            'kendala'            => $hasilAi['kendala'] ?? 'Tidak Terdeteksi',
            'urgensi'            => 'medium',
            'estimasi_sparepart' => $hasilAi['estimasi_sparepart'] ?? 'Dicek Manual',
            'estimasi_biaya'     => $hasilAi['estimasi_biaya'] ?? 0,
            'raw_text'           => $teksAduanUser,
            'email'              => $request->input('email'), // Memasukkan kolom email ke database
            'status'             => 'konsultasi',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return redirect('/konsultasi')->with([
            'success'   => true,
            'ticket_id' => $idTiket,
            'ticket'    => (object) [
                'perangkat'          => $hasilAi['perangkat'] ?? 'Tidak Terdeteksi',
                'kendala'            => $hasilAi['kendala'] ?? 'Tidak Terdeteksi',
                'estimasi_sparepart' => $hasilAi['estimasi_sparepart'] ?? 'Dicek Manual',
                'estimasi_biaya'     => $hasilAi['estimasi_biaya'] ?? 0
            ]
        ]);
    }

    /**
     * Mengubah status dari konsultasi menjadi Antrean Riil & Generate Kode Booking
     */
    public function konfirmasiBooking($id)
    {
        $tahun = date('Y');
        
        // 1. Cari kode booking terakhir yang dibuat pada tahun berjalan
        $bookingTerakhir = DB::table('tickets')
            ->where('kode_booking', 'LIKE', "SRV-{$tahun}-%")
            ->orderBy('kode_booking', 'desc')
            ->first();

        if ($bookingTerakhir) {
            // Mengambil 4 angka terakhir dari kode booking (misal '0002' dari 'SRV-2026-0002')
            $nomorTerakhir = (int) substr($bookingTerakhir->kode_booking, -4);
            $nomorUrut = str_pad($nomorTerakhir + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada booking sama sekali di tahun ini, mulai dari 0001
            $nomorUrut = '0001';
        }

        $kodeBookingBaru = "SRV-" . $tahun . "-" . $nomorUrut;

        // 2. Update status dan kode booking di database
        DB::table('tickets')->where('id', $id)->update([
            'status'       => 'antrean',
            'kode_booking' => $kodeBookingBaru,
            'updated_at'   => now()
        ]);

        // 3. Ambil data tiket terbaru untuk dikirimkan melalui email
        $ticket = DB::table('tickets')->where('id', $id)->first();

        // 4. Eksekusi pengiriman email otomatis ke alamat email pelanggan
        if ($ticket && $ticket->email) {
            Mail::to($ticket->email)->send(new BookingSuksesMail($ticket));
        }

        return redirect('/konsultasi')->with('booking_success_code', $kodeBookingBaru);
    }

    /**
     * Dashboard Teknisi (Hanya memunculkan status 'antrean', 'sedang_diperbaiki', 'selesai')
     */
    public function adminDashboard(Request $request)
    {
        // 1. Tangkap parameter filter status dari URL (jika ada)
        $statusFilter = $request->query('status');

        // 2. Query dasar untuk mengambil data tiket
        $query = DB::table('tickets')->orderBy('created_at', 'desc');

        // 3. Jika filter status dipilih, persempit pencarian data
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $tickets = $query->get();

        // 4. Hitung jumlah data per status untuk indikator angka pada tombol filter
        $counts = [
            'semua'             => DB::table('tickets')->where('status', '!=', 'konsultasi')->count(),
            'antrean'           => DB::table('tickets')->where('status', 'antrean')->count(),
            'sedang_diperbaiki' => DB::table('tickets')->where('status', 'sedang_diperbaiki')->count(),
            'selesai'           => DB::table('tickets')->where('status', 'selesai')->count(),
            'total_pendapatan'  => DB::table('tickets')->where('status', 'selesai')->sum('biaya_final'),
        ];

        return view('admin.dashboard', compact('tickets', 'counts'));
    }

    /**
     * Mengubah status servis perangkat dan menginput biaya final dari teknisi
     */
    public function updateStatus(Request $request, $id)
    {
        $statusBaru = $request->input('status');
        $updateData = [
            'status'     => $statusBaru,
            'updated_at' => now()
        ];

        // Jika statusnya dirubah ke selesai, kita masukkan nilai biaya final manual dari teknisi
        if ($statusBaru == 'selesai') {
            $updateData['biaya_final'] = $request->input('biaya_final');
        }

        // 1. Eksekusi update data ke database seperti biasa
        DB::table('tickets')->where('id', $id)->update($updateData);

        // 2. AMBIL DATA TERBARU: Tarik data tiket dari database setelah di-update
        $ticket = DB::table('tickets')->where('id', $id)->first();

        // 3. PENGKONDISIAN EMAIL: Jika statusnya berubah jadi 'selesai' dan email pelanggan tersedia
        if ($statusBaru == 'selesai' && !empty($ticket->email)) {
            Mail::to($ticket->email)->send(new ServisSelesaiMail($ticket));
        }

        return back()->with('success_update', 'Status perangkat berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman form cek status awal
     */
    public function halamanCekStatus()
    {
        return view('cek-status');
    }

    /**
     * Memproses pencarian Kode Booking unik
     */
    public function prosesCekStatus(Request $request)
    {
        $kodeBooking = $request->input('kode_booking');

        // Cari data berdasarkan kode booking
        $ticket = DB::table('tickets')->where('kode_booking', $kodeBooking)->first();

        if (!$ticket) {
            return back()->with('error_cari', 'Waduh, Kode Booking tidak ditemukan. Periksa kembali penulisan huruf besar/kecil dan angkanya!');
        }

        // Kembalikan ke halaman dengan membawa data ticket yang ditemukan
        return view('cek-status', compact('ticket', 'kodeBooking'));
    }

    // 1. Menampilkan halaman login
    public function showLoginForm()
    {
        // Jika sudah login, langsung lempar ke dashboard admin
        if (Auth::check()) {
            return redirect('/admin');
        }
        return view('admin.login');
    }

    // 2. Memproses verifikasi login
    public function login(Request $request)
    {
        $kredensial = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect('/admin');
        }

        return back()->with('error', 'Email atau password salah, silakan cek kembali.');
    }

    // 3. Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}