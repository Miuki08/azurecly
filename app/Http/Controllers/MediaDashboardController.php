<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaDashboardController extends Controller
{
    public function index()
    {
        $latestTickets = Ticket::with(['images'])
            ->where('Sentiment', 'positive')
            ->orderByDesc('PublishedDate')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $topActors = Ticket::select('Actor', DB::raw('count(*) as total'))
            ->whereNotNull('Actor')
            ->where('Actor', '!=', '')
            ->groupBy('Actor')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topTags = Ticket::select('Tag', DB::raw('count(*) as total'))
            ->whereNotNull('Tag')
            ->where('Tag', '!=', '')
            ->groupBy('Tag')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topRegions = Ticket::select('Region', DB::raw('count(*) as total'))
            ->whereNotNull('Region')
            ->where('Region', '!=', '')
            ->groupBy('Region')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboard.media', compact(
            'latestTickets',
            'topActors',
            'topTags',
            'topRegions'
        ));
    }
}
