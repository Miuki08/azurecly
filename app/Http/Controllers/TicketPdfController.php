<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketPdfController extends Controller
{
    public function export($id)
    {
        $siteId = Auth::user()->site_id;
        
        $ticket = Ticket::with(['creator', 'images'])
            ->where('site_id', $siteId)
            ->findOrFail($id);

        $data = [
            'ticket' => $ticket,
            'generated_at' => now()->format('d F Y, H:i'),
        ];

        $pdf = Pdf::loadView('tickets.pdf', $data);

        $filename = 'news-' . $ticket->id . '-' . Str::slug($ticket->Title) . '.pdf';
        
        return $pdf->download($filename);
    }
}