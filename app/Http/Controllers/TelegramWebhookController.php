<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();

        // Log::info('Telegram update', $update);
        Log::info('telegram', $request->all());

        $message = $update['message'] ?? null;
        if (!$message) {
            return response()->json(['ok' => true]);
        }

        $chat     = $message['chat'] ?? [];
        $from     = $message['from'] ?? [];
        $chatId   = $chat['id'] ?? null;
        $username = $from['username'] ?? null;
        $first    = $from['first_name'] ?? '';
        $last     = $from['last_name'] ?? '';
        $name     = trim($first.' '.$last);

        if (!$chatId) {
            return response()->json(['ok' => true]);
        }

        $siteId = 1;

        $query = Contact::query();

        if ($siteId !== null) {
            $query->where('site_id', $siteId);
        }

        if ($username) {
            $query->where('TelegramUsername', $username);
        } else {
            $query->where('TelegramChatId', $chatId);
        }

        $contact = $query->first();

        if ($contact) {
            $contact->update([
                'TelegramChatId'   => (string) $chatId,
                'TelegramUsername' => $username ?? $contact->TelegramUsername,
            ]);
        } else {
            Contact::create([
                'site_id'          => $siteId,
                'Name'             => $name ?: ($username ? '@'.$username : 'Telegram '.$chatId),
                'Email'            => null,
                'Phone'            => null,
                'Position'         => null,
                'Institution'      => null,
                'Category'         => 'media',
                'Favorite'         => false,
                'Notes'            => 'Auto-created from Telegram bot',
                'Created'          => 1,
                'TelegramUsername' => $username,
                'TelegramChatId'   => (string) $chatId,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}