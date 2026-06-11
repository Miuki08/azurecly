<?php

namespace App\Http\Controllers;

use App\Models\EscalationLog;
use App\Mail\TicketEscalatedMail;
use App\Models\Ticket;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketEscalationController extends Controller
{

    /**
     * Admin idex
    */

    public function indexWeb(Request $request)
    {
        $user   = Auth::user();
        $siteId = $user->site_id;

        $query = EscalationLog::with(['ticket', 'contact', 'escalator'])
            ->where('site_id', $siteId)
            ->latest('SentDate')
            ->latest('created_at');

        if ($request->filled('channel')) {
            $query->where('Channel', $request->channel);
        }

        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Recipient', 'like', "%{$search}%")
                  ->orWhere('Channel', 'like', "%{$search}%")
                  ->orWhere('Status', 'like', "%{$search}%")
                  ->orWhereHas('ticket', function ($qq) use ($search) {
                      $qq->where('Title', 'like', "%{$search}%");
                  })
                  ->orWhereHas('contact', function ($qq) use ($search) {
                      $qq->where('Name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(15);

        return view('escalations.index', [
            'logs' => $logs,
            'mode' => 'admin', 
        ]);
    }

    /**
     * Humas index
     */

    public function myIndexWeb(Request $request)
    {
        $user   = Auth::user();
        $siteId = $user->site_id;

        $query = EscalationLog::with(['ticket', 'contact', 'escalator'])
            ->where('site_id', $siteId)
            ->where('Escalated', $user->id) 
            ->latest('SentDate')
            ->latest('created_at');

        if ($request->filled('channel')) {
            $query->where('Channel', $request->channel);
        }

        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Recipient', 'like', "%{$search}%")
                  ->orWhere('Channel', 'like', "%{$search}%")
                  ->orWhere('Status', 'like', "%{$search}%")
                  ->orWhereHas('ticket', function ($qq) use ($search) {
                      $qq->where('Title', 'like', "%{$search}%");
                  })
                  ->orWhereHas('contact', function ($qq) use ($search) {
                      $qq->where('Name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(15);

        return view('escalations.index', [
            'logs' => $logs,
            'mode' => 'humas',
        ]);
    }

    /**
     * Verify face and delete escalation log
     */

    public function verifyFace(Request $request, EscalationLog $escalation)
    {
        $data = $request->validate([
            'descriptor'   => 'required|array',
            'descriptor.*' => 'numeric',
        ]);

        $user = $request->user();

        if (!$user->FaceDescription || !is_array($user->FaceDescription)) {
            return response()->json([
                'ok'      => false,
                'message' => 'Face is not registered for this admin account.',
            ], 422);
        }

        $stored = $user->FaceDescription;
        $probe  = $data['descriptor'];

        if (count($stored) !== count($probe)) {
            return response()->json([
                'ok'      => false,
                'message' => 'Descriptor length mismatch.',
            ], 422);
        }

        $sum = 0.0;
        $n   = count($stored);
        for ($i = 0; $i < $n; $i++) {
            $d   = $stored[$i] - $probe[$i];
            $sum += $d * $d;
        }
        $distance  = sqrt($sum);
        $threshold = 0.6; 

        if ($distance > $threshold) {
            return response()->json([
                'ok'       => false,
                'message'  => 'Face verification failed.',
                'distance' => $distance,
            ], 403);
        }

        $escalation->delete();

        return response()->json([
            'ok'       => true,
            'message'  => 'Face verified & escalation deleted.',
            'distance' => $distance,
        ]);
    }

    /**
    * entry of data and main logic in the escalation process
    */
    public function store(Request $request, Ticket $ticket)
    {
        $user   = Auth::user();
        $siteId = $user->site_id;

        if ($ticket->site_id !== $siteId) {
            abort(404);
        }

        $data = $request->validate([
            'channel'    => 'required|in:email,whatsapp,both,telegram',
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
                }elseif ($data['channel'] === 'telegram') {
                    $recipient = $contact->TelegramChatId ?? $contact->TelegramUsername ?? null;
                } else { 
                    $recipient = $contact->Email ?: $contact->Phone;
                }
            }
        }

        if (!$recipient) {
            return back()
                ->withInput()
                ->with('error', 'Penerima tidak boleh kosong, isi manual atau pilih kontak yang memiliki email atau nomor atau telegram.');
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

        if (in_array($data['channel'], ['telegram', 'both'], true)) {
            $result = $this->sendTelegramMessage($recipient, $data['message'], $ticket);
            
            if ($result['success']) {
                $log->update([
                    'Status'   => 'sent',
                    'SentDate' => now(),
                    'Response' => trim(($log->Response ?? '') . "\nTelegram Response: " . json_encode($result['response'])),
                ]);
                
                return redirect()
                    ->route('tickets.show', $ticket->id)
                    ->with('success', 'Eskalasi Telegram berhasil dikirim.');
            } else {
                $log->update([
                    'Status'   => 'failed',
                    'Response' => $result['error'],
                ]);
                
                return redirect()
                    ->route('tickets.show', $ticket->id)
                    ->with('error', 'Eskalasi Telegram gagal dikirim: ' . $result['error']);
            }
        }

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Eskalasi berita berhasil dikirim.');
    }

    /**
     * Send message via Telegram Bot
     */
    private function sendTelegramMessage($recipient, $message, $ticket)
    {
        // $token = env('TELEGRAM_BOT_TOKEN');
        $token = config('services.telegram.bot_token');
        
        if (!$token) {
            return [
                'success' => false,
                'error' => 'TELEGRAM_BOT_TOKEN tidak ditemukan!!'
            ];
        }

        $chatId = $recipient;
        
        $text = $message . "\n\n" . '📎 Detail berita: ' . route('tickets.show', $ticket->id);
        
        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);
            
            $responseData = $response->json();
            
            if ($response->successful() && isset($responseData['ok']) && $responseData['ok'] === true) {
                return [
                    'success' => true,
                    'response' => $responseData
                ];
            } else {
                $errorMessage = $responseData['description'] ?? 'Unknown error from Telegram API';
                return [
                    'success' => false,
                    'error' => $errorMessage
                ];
            }
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
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