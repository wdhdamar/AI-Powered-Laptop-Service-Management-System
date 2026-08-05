<?php

namespace App\Http\Controllers;

use App\Http\Requests\CekStatusRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class AiController extends Controller
{
    public function __construct(private TicketService $tickets)
    {
    }

    public function index()
    {
        return view('index');
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $ticket = $this->tickets->createFromComplaint(
                $request->validated('aduan'),
                $request->validated('email'),
            );
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect('/konsultasi')->with([
            'success' => true,
            'ticket_id' => $ticket->id,
            'ticket' => $ticket,
        ]);
    }

    /**
     * Mengubah status dari konsultasi menjadi Antrean Riil & Generate Kode Booking
     */
    public function konfirmasiBooking($id)
    {
        $ticket = $this->tickets->confirmBooking($id);

        return redirect('/konsultasi')->with('booking_success_code', $ticket->kode_booking);
    }

    /**
     * Dashboard Teknisi (Hanya memunculkan status 'antrean', 'sedang_diperbaiki', 'selesai')
     */
    public function adminDashboard(Request $request)
    {
        $statusFilter = $request->query('status');

        $tickets = $this->tickets->dashboardTickets($statusFilter);
        $counts = $this->tickets->dashboardStats();

        return view('admin.dashboard', compact('tickets', 'counts'));
    }

    /**
     * Mengubah status servis perangkat dan menginput biaya final dari teknisi
     */
    public function updateStatus(UpdateTicketStatusRequest $request, $id)
    {
        $this->tickets->updateStatus(
            $id,
            $request->validated('status'),
            $request->validated('biaya_final'),
        );

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
    public function prosesCekStatus(CekStatusRequest $request)
    {
        $kodeBooking = $request->validated('kode_booking');

        $ticket = $this->tickets->findByBookingCode($kodeBooking);

        if (!$ticket) {
            return back()->with('error_cari', 'Waduh, Kode Booking tidak ditemukan. Periksa kembali penulisan huruf besar/kecil dan angkanya!');
        }

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