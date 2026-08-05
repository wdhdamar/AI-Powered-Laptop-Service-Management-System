<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingSuksesMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚀 Booking Servis MarsTop Berhasil - ' . $this->ticket->kode_booking,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-sukses',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}