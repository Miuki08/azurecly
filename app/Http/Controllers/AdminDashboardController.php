<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $siteId = Auth::user()->site_id;

        $base = Ticket::where('site_id', $siteId);

        $stats = [
            'total_berita'       => (clone $base)->count(),
            'berita_hari_ini'    => (clone $base)->whereDate('PublishedDate', today())->count(),
            'positive_sentiment' => (clone $base)->where('Sentiment', 'positive')->count(),
            'negative_sentiment' => (clone $base)->where('Sentiment', 'negative')->count(),
            'high_priority'      => (clone $base)->where('Priority', 'high')->count(),
        ];

        $maps_data = Ticket::where('site_id', $siteId)
            ->whereNotNull('Latitude')
            ->whereNotNull('Longitude')
            ->whereNotNull('Location')
            ->orderBy('PublishedDate', 'desc')
            ->limit(50)
            ->get([
                'id', 'Title', 'Sentiment', 'Priority',
                'Location', 'Latitude', 'Longitude', 'PublishedDate'
            ]);

        $popular_categories = Ticket::select('CategoryId', DB::raw('count(*) as total'))
            ->where('site_id', $siteId)
            ->whereNotNull('CategoryId')
            ->groupBy('CategoryId')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($row) use ($siteId) {
                // Category juga per site (walau ID sudah implicit, ini aman)
                $category = Category::where('site_id', $siteId)->find($row->CategoryId);

                return (object) [
                    'id'    => $row->CategoryId,
                    'name'  => $category?->Name,
                    'total' => $row->total,
                ];
            });

        $high_priority_negative = Ticket::with(['category', 'region'])
            ->where('site_id', $siteId)
            ->where('Priority', 'high')
            ->where('Sentiment', 'negative')
            ->orderBy('PublishedDate', 'desc')
            ->limit(5)
            ->get([
                'id', 'Title', 'Description', 'Location',
                'PublishedDate', 'CategoryId', 'RegionId'
            ]);

        return view('dashboard.index', compact(
            'stats',
            'maps_data',
            'popular_categories',
            'high_priority_negative'
        ));
    }

    public function getMapData()
    {
        $siteId = Auth::user()->site_id;

        $tickets = Ticket::where('site_id', $siteId)
            ->whereNotNull('Latitude')
            ->whereNotNull('Longitude')
            ->orderBy('PublishedDate', 'desc')
            ->limit(100)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $tickets,
        ]);
    }
}
