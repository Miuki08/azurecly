<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Region;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $siteId = Auth::user()->site_id;

        $query = Ticket::with(['creator', 'images'])
            ->where('site_id', $siteId)
            ->where('HandlerType', 1);

        if ($request->filled('sentiment')) {
            $query->where('Sentiment', $request->sentiment);
        }

        if ($request->filled('priority')) {
            $query->where('Priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('CategoryId', $request->category);
        }

        if ($request->filled('region')) {
            $query->where('RegionId', $request->region);
        }

        if ($request->boolean('high_priority_negative')) {
            $query->where('Priority', 'high')
                  ->where('Sentiment', 'negative');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Title', 'LIKE', "%{$search}%")
                  ->orWhere('Description', 'LIKE', "%{$search}%")
                  ->orWhere('Actor', 'LIKE', "%{$search}%");
            });
        }

        $sortField = $request->get('sort_by', 'Created');
        $sortOrder = $request->get('sort_order', 'desc');

        $sortFieldMap = [
            'title'        => 'Title',
            'created_at'   => 'Created',
            'updated_at'   => 'updated_at',
            'priority'     => 'Priority',
            'sentiment'    => 'Sentiment',
            'view_count'   => 'ViewCount',
            'published_at' => 'PublishedDate',
        ];

        $sortField = $sortFieldMap[$sortField] ?? $sortField;
        $query->orderBy($sortField, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $tickets = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => TicketResource::collection($tickets),
            'meta'    => [
                'total'        => $tickets->total(),
                'per_page'     => $tickets->perPage(),
                'current_page' => $tickets->currentPage(),
                'last_page'    => $tickets->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $siteId = Auth::user()->site_id;

        $validator = Validator::make($request->all(), [
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'sentiment'    => 'required|in:positive,neutral,negative',
            'actor'        => 'nullable|string|max:255',
            'category'     => 'required|exists:categories,id',
            'priority'     => 'required|in:high,medium,low',
            'tag'          => 'nullable|string|max:100',
            'region'       => 'nullable|exists:regions,id',
            'location'     => 'nullable|string|max:255',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'HandlerType'   => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $category = Category::where('site_id', $siteId)->findOrFail($request->category);
            $region = $request->region
                ? Region::where('site_id', $siteId)->findOrFail($request->region)
                : null;

            $ticket = Ticket::create([
                'site_id'       => $siteId,
                'Title'         => $request->title,
                'Description'   => $request->description,
                'Sentiment'     => $request->sentiment,
                'Actor'         => $request->actor,
                'CategoryId'    => $request->category,
                'Category'      => $category?->Name,
                'Priority'      => $request->priority,
                'Tag'           => $request->tag,
                'RegionId'      => $request->region,
                'Region'        => $region?->Name,
                'Location'      => $request->location,
                'Latitude'      => $request->latitude,
                'Longitude'     => $request->longitude,
                'PublishedDate' => $request->published_at,
                'Created'       => Auth::id(),
                'ViewCount'     => 0,
                'HandlerType'   => $request->boolean('HandlerType', false),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully',
                'data'    => new TicketResource($ticket->load(['creator', 'images'])),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $siteId = Auth::user()->site_id;

            $ticket = Ticket::with(['creator', 'images'])
                ->where('site_id', $siteId)
                ->where('HandlerType', 1)
                ->findOrFail($id);

            $ticket->increment('ViewCount');

            return response()->json([
                'success' => true,
                'data'    => new TicketResource($ticket),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $siteId = Auth::user()->site_id;

            $ticket = Ticket::where('site_id', $siteId)->findOrFail($id);

            if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this ticket',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title'        => 'sometimes|string|max:255',
                'description'  => 'sometimes|string',
                'sentiment'    => 'sometimes|in:positive,neutral,negative',
                'actor'        => 'nullable|string|max:255',
                'category'     => 'sometimes|exists:categories,id',
                'priority'     => 'sometimes|in:high,medium,low',
                'tag'          => 'nullable|string|max:100',
                'region'       => 'nullable|exists:regions,id',
                'location'     => 'nullable|string|max:255',
                'latitude'     => 'nullable|numeric',
                'longitude'    => 'nullable|numeric',
                'HandlerType'  => 'nullable|boolean',
                'published_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $updateData = [];

            if ($request->has('title'))        $updateData['Title']       = $request->title;
            if ($request->has('description'))  $updateData['Description'] = $request->description;
            if ($request->has('sentiment'))    $updateData['Sentiment']   = $request->sentiment;
            if ($request->has('actor'))        $updateData['Actor']       = $request->actor;

            if ($request->has('category')) {
                $category = Category::where('site_id', $siteId)->findOrFail($request->category);
                $updateData['CategoryId'] = $category->id;
                $updateData['Category']   = $category->Name;
            }

            if ($request->has('priority'))     $updateData['Priority']    = $request->priority;
            if ($request->has('tag'))          $updateData['Tag']         = $request->tag;

            if ($request->has('region')) {
                $region = $request->region
                    ? Region::where('site_id', $siteId)->findOrFail($request->region)
                    : null;

                $updateData['RegionId'] = $region?->id;
                $updateData['Region']   = $region?->Name;
            }

            if ($request->has('HandlerType')) {
                $updateData['HandlerType'] = $request->boolean('HandlerType', false);
            }

            if ($request->has('location'))     $updateData['Location']      = $request->location;
            if ($request->has('latitude'))     $updateData['Latitude']      = $request->latitude;
            if ($request->has('longitude'))    $updateData['Longitude']     = $request->longitude;
            if ($request->has('published_at')) $updateData['PublishedDate'] = $request->published_at;

            $ticket->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully',
                'data'    => new TicketResource($ticket->load(['creator', 'images'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found or update failed',
                'error'   => $e->getMessage(),
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $siteId = Auth::user()->site_id;

            $ticket = Ticket::where('site_id', $siteId)->findOrFail($id);

            if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this ticket',
                ], 403);
            }

            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found',
            ], 404);
        }
    }

    public function statistics()
    {
        $siteId = Auth::user()->site_id;

        $base = Ticket::where('site_id', $siteId)
            ->where('HandlerType', 1);

        $stats = [
            'total' => (clone $base)->count(),
            'by_sentiment' => [
                'positive' => (clone $base)->where('Sentiment', 'positive')->count(),
                'neutral'  => (clone $base)->where('Sentiment', 'neutral')->count(),
                'negative' => (clone $base)->where('Sentiment', 'negative')->count(),
            ],
            'by_priority' => [
                'high'   => (clone $base)->where('Priority', 'high')->count(),
                'medium' => (clone $base)->where('Priority', 'medium')->count(),
                'low'    => (clone $base)->where('Priority', 'low')->count(),
            ],
            'high_priority_negative' => (clone $base)
                ->where('Priority', 'high')
                ->where('Sentiment', 'negative')
                ->count(),
            'total_views' => Ticket::sum('ViewCount'),
        ];

        return response()->json([
            'success' => true,
            'data'    => $stats,
        ]);
    }
}