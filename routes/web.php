<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;

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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'indexWeb'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'createWeb'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'storeWeb'])->name('tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'showWeb'])->name('tickets.show');
    Route::get('/tickets/{id}/edit', [TicketController::class, 'editWeb'])->name('tickets.edit');
    Route::put('/tickets/{id}', [TicketController::class, 'updateWeb'])->name('tickets.update');
    Route::delete('/tickets/{id}', [TicketController::class, 'destroyWeb'])->name('tickets.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/api/map-data', [DashboardController::class, 'getMapData'])
    ->middleware('auth');

require __DIR__.'/auth.php';
