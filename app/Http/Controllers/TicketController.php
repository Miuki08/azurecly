<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with('creator');

        // Filter by sentiment
        if ($request->has('sentiment')) {
            $query->where('Sentiment', $request->sentiment);  
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('Priority', $request->priority);    
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('Category', $request->category);    
        }

        // Filter by region (NEW)
        if ($request->has('region')) {
            $query->where('Region', 'LIKE', "%{$request->region}%");
        }

        // Filter high priority negative
        if ($request->has('high_priority_negative') && $request->high_priority_negative) {
            $query->where('Priority', 'high')
                  ->where('Sentiment', 'negative');
        }

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Title', 'LIKE', "%{$search}%")
                  ->orWhere('Description', 'LIKE', "%{$search}%")
                  ->orWhere('Actor', 'LIKE', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort_by', 'Created');  
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Mapping sort field jika perlu
        $sortFieldMap = [
            'title' => 'Title',
            'created_at' => 'Created',
            'updated_at' => 'updated_at',
            'priority' => 'Priority',
            'sentiment' => 'Sentiment',
            'view_count' => 'ViewCount',
            'published_at' => 'PublishedDate',
        ];
        
        $sortField = $sortFieldMap[$sortField] ?? $sortField;
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $tickets = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => TicketResource::collection($tickets),
            'meta' => [
                'total' => $tickets->total(),
                'per_page' => $tickets->perPage(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
            ]
        ]);
    }

    /**
     * Display a listing of tickets (WEB)
     */
    public function indexWeb(Request $request)
    {
        $query = Ticket::with('creator');
        
        // Filter by sentiment
        if ($request->has('sentiment') && $request->sentiment) {
            $query->where('Sentiment', $request->sentiment);
        }
        
        // Filter by priority
        if ($request->has('priority') && $request->priority) {
            $query->where('Priority', $request->priority);
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('Category', 'LIKE', "%{$request->category}%");
        }
        
        // Search by title or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Title', 'LIKE', "%{$search}%")
                  ->orWhere('Description', 'LIKE', "%{$search}%")
                  ->orWhere('Actor', 'LIKE', "%{$search}%");
            });
        }
        
        $tickets = $query->orderBy('Created', 'desc')->paginate(15);
        
        return view('tickets.index', compact('tickets'));
    }
    
    /**
     * Show form create ticket (WEB)
     */
    public function createWeb()
    {
        return view('tickets.create');
    }
    
    /**
     * Store a newly created ticket (WEB)
     */
    public function storeWeb(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sentiment' => 'required|in:positive,neutral,negative',
            'actor' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'priority' => 'required|in:high,medium,low',
            'tag' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'published_at' => 'nullable|date',
        ]);
        
        $validated['Title'] = $validated['title'];
        $validated['Description'] = $validated['description'];
        $validated['Sentiment'] = $validated['sentiment'];
        $validated['Actor'] = $validated['actor'] ?? null;
        $validated['Category'] = $validated['category'];
        $validated['Priority'] = $validated['priority'];
        $validated['Tag'] = $validated['tag'] ?? null;
        $validated['Region'] = $validated['region'] ?? null;
        $validated['Location'] = $validated['location'] ?? null;
        $validated['Latitude'] = $validated['latitude'] ?? null;
        $validated['Longitude'] = $validated['longitude'] ?? null;
        $validated['PublishedDate'] = $validated['published_at'] ?? null;
        $validated['Created'] = Auth::id();
        $validated['ViewCount'] = 0;
        
        // Hapus key dengan format camelCase
        unset($validated['title'], $validated['description'], $validated['sentiment'], 
              $validated['actor'], $validated['category'], $validated['priority'], 
              $validated['tag'], $validated['region'], $validated['location'], 
              $validated['latitude'], $validated['longitude'], $validated['published_at']);
        
        Ticket::create($validated);
        
        return redirect()->route('tickets.index')->with('success', 'Berita berhasil ditambahkan');
    }
    
    /**
     * Display the specified ticket (WEB)
     */
    public function showWeb($id)
    {
        $ticket = Ticket::with('creator')->findOrFail($id);
        $ticket->increment('ViewCount');
        
        return view('tickets.show', compact('ticket'));
    }
    
    /**
     * Show form edit ticket (WEB)
     */
    public function editWeb($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.edit', compact('ticket'));
    }
    
    /**
     * Update the specified ticket (WEB)
     */
    public function updateWeb(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Authorization check
        if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('tickets.index')->with('error', 'Unauthorized to update this ticket');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sentiment' => 'required|in:positive,neutral,negative',
            'actor' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'priority' => 'required|in:high,medium,low',
            'tag' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'published_at' => 'nullable|date',
        ]);
        
        $updateData = [];
        if ($request->has('title')) $updateData['Title'] = $validated['title'];
        if ($request->has('description')) $updateData['Description'] = $validated['description'];
        if ($request->has('sentiment')) $updateData['Sentiment'] = $validated['sentiment'];
        if ($request->has('actor')) $updateData['Actor'] = $validated['actor'];
        if ($request->has('category')) $updateData['Category'] = $validated['category'];
        if ($request->has('priority')) $updateData['Priority'] = $validated['priority'];
        if ($request->has('tag')) $updateData['Tag'] = $validated['tag'];
        if ($request->has('region')) $updateData['Region'] = $validated['region'];
        if ($request->has('location')) $updateData['Location'] = $validated['location'];
        if ($request->has('latitude')) $updateData['Latitude'] = $validated['latitude'];
        if ($request->has('longitude')) $updateData['Longitude'] = $validated['longitude'];
        if ($request->has('published_at')) $updateData['PublishedDate'] = $validated['published_at'];
        
        $ticket->update($updateData);
        
        return redirect()->route('tickets.index')->with('success', 'Berita berhasil diupdate');
    }
    
    /**
     * Remove the specified ticket (WEB)
     */
    public function destroyWeb($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('tickets.index')->with('error', 'Unauthorized to delete this ticket');
        }
        
        $ticket->delete();
        
        return redirect()->route('tickets.index')->with('success', 'Berita berhasil dihapus');
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sentiment' => 'required|in:positive,neutral,negative',
            'actor' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'priority' => 'required|in:high,medium,low',
            'tag' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',       
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ticket = Ticket::create([
                'Title' => $request->title,                 
                'Description' => $request->description,     
                'Sentiment' => $request->sentiment,         
                'Actor' => $request->actor,                 
                'Category' => $request->category,           
                'Priority' => $request->priority,           
                'Tag' => $request->tag,                     
                'Region' => $request->region,                
                'Location' => $request->location,           
                'Latitude' => $request->latitude,           
                'Longitude' => $request->longitude,         
                'PublishedDate' => $request->published_at,  
                'Created' => Auth::id(),                     
                'ViewCount' => 0,                            
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully',
                'data' => new TicketResource($ticket->load('creator'))
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        try {
            $ticket = Ticket::with('creator')->findOrFail($id);
            
            // Increment view count
            $ticket->increment('ViewCount');  

            return response()->json([
                'success' => true,
                'data' => new TicketResource($ticket)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
    }

    /**
     * Update the specified ticket.
     */
    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {  
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this ticket'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'sentiment' => 'sometimes|in:positive,neutral,negative',
                'actor' => 'nullable|string|max:255',
                'category' => 'sometimes|string|max:100',
                'priority' => 'sometimes|in:high,medium,low',
                'tag' => 'nullable|string|max:100',
                'region' => 'nullable|string|max:100',
                'location' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'published_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];
            
            if ($request->has('title')) $updateData['Title'] = $request->title;
            if ($request->has('description')) $updateData['Description'] = $request->description;
            if ($request->has('sentiment')) $updateData['Sentiment'] = $request->sentiment;
            if ($request->has('actor')) $updateData['Actor'] = $request->actor;
            if ($request->has('category')) $updateData['Category'] = $request->category;
            if ($request->has('priority')) $updateData['Priority'] = $request->priority;
            if ($request->has('tag')) $updateData['Tag'] = $request->tag;
            if ($request->has('region')) $updateData['Region'] = $request->region;
            if ($request->has('location')) $updateData['Location'] = $request->location;
            if ($request->has('latitude')) $updateData['Latitude'] = $request->latitude;
            if ($request->has('longitude')) $updateData['Longitude'] = $request->longitude;
            if ($request->has('published_at')) $updateData['PublishedDate'] = $request->published_at;

            $ticket->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully',
                'data' => new TicketResource($ticket->load('creator'))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found or update failed',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {  
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this ticket'
                ], 403);
            }

            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total' => Ticket::count(),
            'by_sentiment' => [
                'positive' => Ticket::where('Sentiment', 'positive')->count(),    
                'neutral' => Ticket::where('Sentiment', 'neutral')->count(),      
                'negative' => Ticket::where('Sentiment', 'negative')->count(),    
            ],
            'by_priority' => [
                'high' => Ticket::where('Priority', 'high')->count(),              
                'medium' => Ticket::where('Priority', 'medium')->count(),          
                'low' => Ticket::where('Priority', 'low')->count(),                
            ],
            'by_region' => [                                                       // <-- TAMBAHAN BARU
                'jakarta' => Ticket::where('Region', 'LIKE', '%jakarta%')->count(),
                'bandung' => Ticket::where('Region', 'LIKE', '%bandung%')->count(),
                'surabaya' => Ticket::where('Region', 'LIKE', '%surabaya%')->count(),
            ],
            'high_priority_negative' => Ticket::where('Priority', 'high')
                                               ->where('Sentiment', 'negative')
                                               ->count(),
            'popular_categories' => Ticket::select('Category')                    
                ->selectRaw('count(*) as total')
                ->groupBy('Category')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get(),
            'total_views' => Ticket::sum('ViewCount'),                             
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}