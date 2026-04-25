<?php

use App\Http\Controllers\TicketEscalationController;
// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\HumasDashboardController;
use App\Http\Controllers\MediaDashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminSettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/humas', [HumasDashboardController::class, 'index'])
        ->middleware('role:humas,admin')
        ->name('dashboard.humas');

    Route::get('/dashboard/media', [MediaDashboardController::class, 'index'])
        ->middleware('role:media,admin')
        ->name('dashboard.media');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('dashboard.admin'),
        'media' => redirect()->route('dashboard.media'),
        default => redirect()->route('dashboard.humas'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])
        ->name('dashboard.admin');

    Route::get('/admin/settings', [AdminSettingsController::class, 'index'])
        ->name('admin.settings.index');

    Route::post('/admin/settings/site', [AdminSettingsController::class, 'storeSite'])
        ->name('admin.settings.site.store');
    Route::put('/admin/settings/site/{site}', [AdminSettingsController::class, 'updateSite'])
        ->name('admin.settings.site.update');

    Route::post('/admin/settings/regions', [AdminSettingsController::class, 'storeRegion'])
        ->name('admin.settings.regions.store');
    Route::delete('/admin/settings/regions/{region}', [AdminSettingsController::class, 'destroyRegion'])
        ->name('admin.settings.regions.destroy');

    Route::post('/admin/settings/categories', [AdminSettingsController::class, 'storeCategory'])
        ->name('admin.settings.categories.store');
    Route::delete('/admin/settings/categories/{category}', [AdminSettingsController::class, 'destroyCategory'])
        ->name('admin.settings.categories.destroy');

    Route::post('/admin/settings/users', [AdminSettingsController::class, 'storeUser'])
        ->name('admin.settings.users.store');
});

Route::middleware(['auth', 'role:humas,admin'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'indexWeb'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'createWeb'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'storeWeb'])->name('tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'showWeb'])->name('tickets.show');
    Route::get('/tickets/{id}/edit', [TicketController::class, 'editWeb'])->name('tickets.edit');
    Route::put('/tickets/{id}', [TicketController::class, 'updateWeb'])->name('tickets.update');
    Route::delete('/tickets/{id}', [TicketController::class, 'destroyWeb'])->name('tickets.destroy');
    Route::post('/tickets/{ticket}/escalate', [TicketEscalationController::class, 'store'])->name('tickets.escalate');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/contacts', [ContactController::class, 'indexWeb'])->name('contacts.index');
    Route::get('/contacts/create', [ContactController::class, 'createWeb'])->name('contacts.create');
    Route::post('/contacts', [ContactController::class, 'storeWeb'])->name('contacts.store');
    Route::get('/contacts/{contact}/edit', [ContactController::class, 'editWeb'])->name('contacts.edit');
    Route::put('/contacts/{contact}', [ContactController::class, 'updateWeb'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroyWeb'])->name('contacts.destroy');
});

Route::get('/api/map-data', [AdminDashboardController::class, 'getMapData'])
    ->middleware(['auth', 'role:admin']);
    
require __DIR__.'/auth.php';
