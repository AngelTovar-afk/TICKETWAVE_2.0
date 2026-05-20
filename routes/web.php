<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MisBoletosController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/eventos', fn() => view('eventos'))->name('eventos.index');
Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');

// Endpoint búsqueda en tiempo real
Route::get('/buscar-eventos', function (\Illuminate\Http\Request $request) {
    $q = $request->query('q', '');
    if (strlen($q) < 2) return response()->json([]);

    $eventos = \App\Models\Event::with(['venue', 'ticketTypes'])
        ->where('status', 'published')
        ->where('event_date', '>', now())
        ->where(fn($query) => $query
            ->where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
        )
        ->orderBy('event_date')
        ->take(6)
        ->get()
        ->map(fn($e) => [
            'id'      => $e->id,
            'nombre'  => $e->name,
            'fecha'   => \Carbon\Carbon::parse($e->event_date)->format('d M Y'),
            'recinto' => $e->venue?->name ?? '',
            'precio'  => $e->ticketTypes->min('price')
                ? 'Desde $' . number_format($e->ticketTypes->min('price'), 0) . ' MXN'
                : '',
            'url'     => route('eventos.show', $e),
        ]);

    return response()->json($eventos);
})->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::get('/mis-boletos', [MisBoletosController::class, 'index'])->name('mis-boletos');
    Route::get('/favoritos', fn() => view('favoritos'))->name('favoritos');
    Route::get('/ajustes',   fn() => view('ajustes'))->name('ajustes');
    Route::get('/eventos/{evento}/checkout', \App\Livewire\CheckoutWizard::class)
        ->name('eventos.checkout');
});

require __DIR__ . '/auth.php';