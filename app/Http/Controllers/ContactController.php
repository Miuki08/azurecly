<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function indexWeb(Request $request)
    {
        $siteId = Auth::user()->site_id;

        $query = Contact::with('creator')
            ->where('site_id', $siteId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', "%{$search}%")
                ->orWhere('Email', 'LIKE', "%{$search}%")
                ->orWhere('Phone', 'LIKE', "%{$search}%")
                ->orWhere('Institution', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('Category', $request->category);
        }

        if ($request->filled('favorite')) {
            $query->where('Favorite', $request->favorite == '1');
        }

        $contacts = $query->orderBy('Name')->paginate(15);

        $categories = ['humas', 'media', 'pimpinan', 'lainnya'];

        return view('contacts.index', compact('contacts', 'categories'));
    }


    public function createWeb()
    {
        $categories = ['humas', 'media', 'pimpinan', 'lainnya'];

        return view('contacts.create', compact('categories'));
    }

    public function storeWeb(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'position'    => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'category'    => 'required|string|max:50',
            'favorite'    => 'nullable|boolean',
            'notes'       => 'nullable|string',
        ]);

        Contact::create([
            'site_id'     => Auth::user()->site_id,
            'Name'        => $validated['name'],
            'Email'       => $validated['email'] ?? null,
            'Phone'       => $validated['phone'] ?? null,
            'Position'    => $validated['position'] ?? null,
            'Institution' => $validated['institution'] ?? null,
            'Category'    => $validated['category'],
            'Favorite'    => $request->boolean('favorite'),
            'Notes'       => $validated['notes'] ?? null,
            'Created'     => Auth::id(),
        ]);

        return redirect()->route('contacts.index')->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function editWeb(Contact $contact)
    {
        if ($contact->site_id !== Auth::user()->site_id) {
            abort(404); 
        }
        $categories = ['humas', 'media', 'pimpinan', 'lainnya'];

        return view('contacts.edit', compact('contact', 'categories'));
    }

    public function updateWeb(Request $request, Contact $contact)
    {
        if ($contact->site_id !== Auth::user()->site_id) {
            return redirect()->route('contacts.index')->with('error', 'Unauthorized to update this contact');
        }

        if ($contact->Created != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('contacts.index')->with('error', 'Unauthorized to update this contact');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'position'    => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'category'    => 'required|string|max:50',
            'favorite'    => 'nullable|boolean',
            'notes'       => 'nullable|string',
        ]);

        $contact->update([
            'Name'        => $validated['name'],
            'Email'       => $validated['email'] ?? null,
            'Phone'       => $validated['phone'] ?? null,
            'Position'    => $validated['position'] ?? null,
            'Institution' => $validated['institution'] ?? null,
            'Category'    => $validated['category'],
            'Favorite'    => $request->boolean('favorite'),
            'Notes'       => $validated['notes'] ?? null,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Kontak berhasil diupdate.');
    }

    public function destroyWeb(Contact $contact)
    {
        if ($contact->site_id !== Auth::user()->site_id) {
            return redirect()->route('contacts.index')->with('error', 'Unauthorized to delete this contact');
        }

        if ($contact->Created != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('contacts.index')->with('error', 'Unauthorized to delete this contact');
        }

        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Kontak berhasil dihapus.');
    }

}
