<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\EscalationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketEscalatedMail extends Mailable
{
    use Queueable, SerializesModels;
    public Ticket $ticket;
    public EscalationLog $log;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, EscalationLog $log)
    {
        $this->ticket = $ticket;
        $this->log    = $log;
    }

    public function build()
    {
        return $this->subject('Eskalasi Berita: ' . $this->ticket->Title)
            ->markdown('emails.tickets.escalated');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket Escalated Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tickets.escalated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
