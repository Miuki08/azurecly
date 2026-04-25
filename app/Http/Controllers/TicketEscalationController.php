<?php

namespace App\Http\Controllers;

use App\Models\EscalationLog;
use App\Mail\TicketEscalatedMail;
use App\Models\Ticket;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TicketEscalationController extends Controller
{
    
    public function store(Request $request, Ticket $ticket)
    {
        $user   = Auth::user();
        $siteId = $user->site_id;

        // hardening: pastikan ticket milik site user
        if ($ticket->site_id !== $siteId) {
            abort(404);
        }

        $data = $request->validate([
            'channel'    => 'required|in:email,whatsapp,both',
            'contact_id' => 'nullable|exists:contacts,id',
            'recipient'  => 'nullable|string|max:255',
            'message'    => 'required|string',
        ]);

        $recipient = $data['recipient'];

        $contact = null;
        if ($data['contact_id']) {
            $contact = Contact::where('site_id', $siteId)
                ->findOrFail($data['contact_id']);

            if (!$recipient && $contact) {
                if ($data['channel'] === 'email') {
                    $recipient = $contact->Email;
                } elseif ($data['channel'] === 'whatsapp') {
                    $recipient = $contact->Phone;
                } else { 
                    $recipient = $contact->Email ?: $contact->Phone;
                }
            }
        }

        if (!$recipient) {
            return back()
                ->withInput()
                ->with('error', 'Penerima tidak boleh kosong, isi manual atau pilih kontak yang memiliki email / nomor.');
        }

        $log = EscalationLog::create([
            'site_id'   => $siteId,
            'TicketId'  => $ticket->id,
            'ContactId' => $contact?->id,
            'Channel'   => $data['channel'],
            'Message'   => $data['message'],
            'Recipient' => $recipient,
            'Status'    => 'pending',
            'Escalated' => Auth::id(),
        ]);

        if (in_array($data['channel'], ['email', 'both'], true)) {
            try {
                Mail::to($recipient)->send(new TicketEscalatedMail($ticket, $log));

                $log->update([
                    'Status'   => 'sent',
                    'SentDate' => now(),
                ]);
            } catch (\Throwable $e) {
                $log->update([
                    'Status'   => 'failed',
                    'Response' => $e->getMessage(),
                ]);

                return redirect()
                    ->route('tickets.show', $ticket->id)
                    ->with('error', 'Eskalasi email gagal dikirim: ' . $e->getMessage());
            }
        }

        if (in_array($data['channel'], ['whatsapp', 'both'], true)) {
            $phone = $this->normalizeIDPhone($recipient);

            if (!$phone) {
                return redirect()
                    ->route('tickets.show', $ticket->id)
                    ->with('error', 'Format nomor WhatsApp tidak valid.');
            }

            $text = $data['message'] . "\n\n" . 'Detail berita: ' . route('tickets.show', $ticket->id);
            $waUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($text);

            $log->update([
                'Response' => trim(($log->Response ?? '') . "\nWA URL: " . $waUrl),
            ]);

            return redirect()->away($waUrl);
        }

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Eskalasi berita berhasil dikirim.');
    }

    private function normalizeIDPhone(string $raw): ?string
    {
        $number = preg_replace('/\D+/', '', $raw);

        if (!$number) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        if (str_starts_with($number, '62')) {
            return $number;
        }

        if (str_starts_with($number, '8')) {
            return '62' . $number;
        }

        return $number;
    }
}