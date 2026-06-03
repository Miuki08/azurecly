<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Region;
use App\Models\TicketImage;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $siteId = Auth::user()->site_id;

        $query = Ticket::with('creator')
            ->where('site_id', $siteId);

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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Title', 'LIKE', "%{$search}%")
                    ->orWhere('Description', 'LIKE', "%{$search}%")
                    ->orWhere('Actor', 'LIKE', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('Created', 'desc')->paginate(15);

        $categories = Category::orderBy('Name')->get();
        $regions    = Region::orderBy('Name')->get();

        return view('tickets.index', compact('tickets', 'categories', 'regions'));
    }

    public function create()
    {
        $siteId = Auth::user()->site_id;

        $categories = Category::where('site_id', $siteId)
            ->orderBy('Name')
            ->get();

        $regions = Region::where('site_id', $siteId)
            ->orderBy('Name')
            ->get();

        return view('tickets.create', compact('categories', 'regions'));
    }

    public function store(Request $request)
    {
        $siteId = Auth::user()->site_id;

        $validated = $request->validate([
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
            'published_at' => 'nullable|date',
            'images'       => 'nullable|array|max:5',
            'images.*'     => 'mimes:jpg,jpeg,png,webp,gif|max:2048'
        ]);

        $category = Category::where('site_id', $siteId)
            ->findOrFail($validated['category']);

        $region = null;
        if (!empty($validated['region'])) {
            $region = Region::where('site_id', $siteId)
                ->findOrFail($validated['region']);
        }

        $ticket = Ticket::create([
            'site_id'       => $siteId,
            'Title'         => $validated['title'],
            'Description'   => $validated['description'],
            'Sentiment'     => $validated['sentiment'],
            'Actor'         => $validated['actor'] ?? null,
            'CategoryId'    => $validated['category'],
            'Category'      => $category?->Name,
            'Priority'      => $validated['priority'],
            'Tag'           => $validated['tag'] ?? null,
            'RegionId'      => $validated['region'] ?? null,
            'Region'        => $region?->Name,
            'Location'      => $validated['location'] ?? null,
            'Latitude'      => $validated['latitude'] ?? null,
            'Longitude'     => $validated['longitude'] ?? null,
            'PublishedDate' => $validated['published_at'] ?? null,
            'Created'       => Auth::id(),
            'ViewCount'     => 0,
            'HandlerType'      => 0,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('tickets', 'public');

                $ticket->images()->create([
                    'site_id'     => $siteId,
                    'Path'        => $path,
                    'Description' => $file->getClientOriginalName(),
                    'Order'       => $index,
                ]);
            }
        }

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    public function show($id)
    {
        $siteId = Auth::user()->site_id;

        $ticket = Ticket::with(['creator', 'images'])
            ->where('site_id', $siteId)
            // ->where('HandlerType', 0)
            ->findOrFail($id);

        $contacts = Contact::where('site_id', $siteId)
            ->orderBy('Name')
            ->get();

        $ticket->increment('ViewCount');

        return view('tickets.show', compact('ticket', 'contacts'));
    }

    public function edit($id)
    {
        $siteId = Auth::user()->site_id;

        $ticket = Ticket::with('images')
            ->where('site_id', $siteId)
            ->findOrFail($id);

        $categories = Category::where('site_id', $siteId)
            ->orderBy('Name')
            ->get();

        $regions = Region::where('site_id', $siteId)
            ->orderBy('Name')
            ->get();

        return view('tickets.edit', compact('ticket', 'categories', 'regions'));
    }

    public function update(Request $request, $id)
    {
        $siteId = Auth::user()->site_id;

        $ticket = Ticket::where('site_id', $siteId)->findOrFail($id);

        if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('tickets.index')->with('error', 'Unauthorized to update this ticket');
        }

        $validated = $request->validate([
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
            'published_at' => 'nullable|date',
            'image'        => 'nullable|array|max:5',
            'images.*'     => 'mimes:jpg,jpeg,png,webp,gif|max:2048',
            'HandlerType'    => 'nullable|boolean',
        ]);

        $updateData = [];

        if ($request->has('title'))        $updateData['Title']         = $validated['title'];
        if ($request->has('description'))  $updateData['Description']   = $validated['description'];
        if ($request->has('sentiment'))    $updateData['Sentiment']     = $validated['sentiment'];
        if ($request->has('actor'))        $updateData['Actor']         = $validated['actor'];
        if ($request->has('priority'))     $updateData['Priority']      = $validated['priority'];
        if ($request->has('tag'))          $updateData['Tag']           = $validated['tag'];
        if ($request->has('location'))     $updateData['Location']      = $validated['location'];
        if ($request->has('latitude'))     $updateData['Latitude']      = $validated['latitude'];
        if ($request->has('longitude'))    $updateData['Longitude']     = $validated['longitude'];
        if ($request->has('published_at')) $updateData['PublishedDate'] = $validated['published_at'];

        if ($request->has('category')) {
            $category = Category::find($validated['category']);
            $updateData['CategoryId'] = $validated['category'];
            $updateData['Category']   = $category?->Name;
        }

        if ($request->has('region')) {
            $region = $validated['region'] ? Region::find($validated['region']) : null;
            $updateData['RegionId'] = $validated['region'];
            $updateData['Region']   = $region?->Name;
        }

        if ($request->has('HandlerType')) {
            $updateData['HandlerType'] = $request->boolean('HandlerType');
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tickets', 'public');
            $updateData['Image'] = $imagePath;
        }

        $ticket->update($updateData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('tickets', 'public');

                $ticket->images()->create([
                    'site_id'     => $siteId,
                    'Path'        => $path,
                    'Description' => $file->getClientOriginalName(),
                    'Order'       => $ticket->images()->max('Order') + 1 + $index,
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Berita berhasil diupdate');
    }

    public function destroy($id)
    {
        $siteId = Auth::user()->site_id;

        $ticket = Ticket::where('site_id', $siteId)->findOrFail($id);

        if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('tickets.index')->with('error', 'Unauthorized to delete this ticket');
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Berita berhasil dihapus');
    }

    public function updateVisibility(Request $request, $id)
    {
        $siteId = Auth::user()->site_id;

        $ticket = Ticket::where('site_id', $siteId)->findOrFail($id);

        if ($ticket->Created != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('error', 'Unauthorized to change visibility for this ticket');
        }

        $validated = $request->validate([
            'HandlerType' => 'required|in:0,1',
        ]);

        $ticket->update([
            'HandlerType' => $validated['HandlerType'],
        ]);

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', $validated['HandlerType']
                ? 'Berita sekarang tampil di public.'
                : 'Berita sekarang hanya tampil di dashboard internal.');
    }
}