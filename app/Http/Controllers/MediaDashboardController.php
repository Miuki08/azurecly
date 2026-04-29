<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MediaDashboardController extends Controller
{
 public function index()
    {
        $siteId = Auth::user()->site_id;

        $latestTickets = Ticket::with(['images'])
            ->where('site_id', $siteId)
            ->where('Sentiment', 'positive')
            ->orderByDesc('PublishedDate')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $topActors = Ticket::select('Actor', DB::raw('count(*) as total'))
            ->where('site_id', $siteId)
            ->whereNotNull('Actor')
            ->where('Actor', '!=', '')
            ->groupBy('Actor')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topTags = Ticket::select('Tag', DB::raw('count(*) as total'))
            ->where('site_id', $siteId)
            ->whereNotNull('Tag')
            ->where('Tag', '!=', '')
            ->groupBy('Tag')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topRegions = Ticket::select('Region', DB::raw('count(*) as total'))
            ->where('site_id', $siteId)
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

