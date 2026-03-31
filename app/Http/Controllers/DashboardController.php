<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk dashboard
        $stats = [
            'total_berita' => Ticket::count(),
            'berita_hari_ini' => Ticket::whereDate('PublishedDate', today())->count(),
            'positive_sentiment' => Ticket::where('Sentiment', 'positive')->count(),
            'negative_sentiment' => Ticket::where('Sentiment', 'negative')->count(),
            'high_priority' => Ticket::where('Priority', 'high')->count(),
        ];

        $maps_data = Ticket::whereNotNull('Latitude')
                          ->whereNotNull('Longitude')
                          ->whereNotNull('Location')
                          ->orderBy('PublishedDate', 'desc')
                          ->limit(50)
                          ->get(['id', 'Title', 'Sentiment', 'Priority', 
                                'Location', 'Latitude', 'Longitude', 'PublishedDate']);

        $popular_categories = Ticket::select('CategoryId', DB::raw('count(*) as total'))
            ->whereNotNull('CategoryId')
            ->groupBy('CategoryId')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $category = Category::find($row->CategoryId);

                return (object) [
                    'id'    => $row->CategoryId,
                    'name'  => $category?->Name,
                    'total' => $row->total,
                ];
            });

        $high_priority_negative = Ticket::with(['category', 'region'])
            ->where('Priority', 'high')
            ->where('Sentiment', 'negative')
            ->orderBy('PublishedDate', 'desc')
            ->limit(5)
            ->get(['id', 'Title', 'Description', 'Location', 'PublishedDate', 'CategoryId', 'RegionId']);

        return view('dashboard.index', compact(
            'stats', 'maps_data', 'popular_categories', 'high_priority_negative'
        ));
    }

    public function getMapData()
    {
        $tickets = Ticket::whereNotNull('Latitude')
                        ->whereNotNull('Longitude')
                        ->orderBy('PublishedDate', 'desc')
                        ->limit(100)
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $tickets
        ]);
    }
}