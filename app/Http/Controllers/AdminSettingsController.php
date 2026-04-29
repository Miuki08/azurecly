<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Region;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $site = $user->site ?? null;

        $regions = $site
            ? Region::where('site_id', $site->id)->orderBy('Name')->get()
            : collect();

        $categories = $site
            ? Category::where('site_id', $site->id)->orderBy('Name')->get()
            : collect();

        $users = $site
            ? User::where('site_id', $site->id)->orderBy('name')->get()
            : collect();

        return view('admin.settings.index', compact('site', 'regions', 'categories', 'users'));
    }


    public function storeSite(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'slug'  => ['required', 'string', 'max:255', 'unique:sites,slug'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $site = Site::create($validated);

        $user->site_id = $site->id;
        $user->save();

        return redirect()->route('admin.settings.index')
            ->with('status', 'Profil perusahaan berhasil dibuat.');
    }

    public function updateSite(Request $request, Site $site)
    {
        $this->authorizeSite($site);

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'slug'  => ['required', 'string', 'max:255', 'unique:sites,slug,' . $site->id],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $site->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('status', 'Profil perusahaan berhasil diperbarui.');
    }

    public function storeRegion(Request $request)
    {
        $site = Auth::user()->site;

        $request->validate([
            'Name' => ['required', 'string', 'max:255'],
            'Code' => ['nullable', 'string', 'max:50'],
            'Type' => ['nullable', 'string', 'max:50'],
        ]);

        Region::create([
            'Name'    => $request->Name,
            'Code'    => $request->Code,
            'Type'    => $request->Type,
            'site_id' => $site->id,
        ]);

        return redirect()->route('admin.settings.index')
            ->with('status', 'Region berhasil ditambahkan.');
    }

    public function destroyRegion(Region $region)
    {
        $this->authorizeSite($region->site);
        $region->delete();

        return redirect()->route('admin.settings.index')
            ->with('status', 'Region berhasil dihapus.');
    }

    public function storeCategory(Request $request)
    {
        $site = Auth::user()->site;

        $request->validate([
            'Name'        => ['required', 'string', 'max:255'],
            'Slug'        => ['required', 'string', 'max:255'],
            'Description' => ['nullable', 'string'],
        ]);

        Category::create([
            'Name'        => $request->Name,
            'Slug'        => $request->Slug,
            'Description' => $request->Description,
            'site_id'     => $site->id,
        ]);

        return redirect()->route('admin.settings.index')
            ->with('status', 'Category berhasil ditambahkan.');
    }

    public function destroyCategory(Category $category)
    {
        $this->authorizeSite($category->site);
        $category->delete();

        return redirect()->route('admin.settings.index')
            ->with('status', 'Category berhasil dihapus.');
    }


    public function storeUser(Request $request)
    {
        $site = Auth::user()->site;

        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'                  => ['required', 'in:admin,humas,media'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'site_id'  => $site->id,
        ]);

        return redirect()->route('admin.settings.index')
            ->with('status', 'Pengguna baru berhasil dibuat.');
    }

    protected function authorizeSite(?Site $site)
    {
        $userSite = Auth::user()->site;
        if (!$site || !$userSite || $site->id !== $userSite->id) {
            abort(403);
        }
    }
}