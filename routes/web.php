<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;

Route::get('/', function () {
  return view('welcome');
});
Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
  Route::get('/mis-boletos', fn() => view('dashboard'))->name('mis-boletos');
  Route::get('/favoritos',   fn() => view('dashboard'))->name('favoritos');
  Route::get('/ajustes',     fn() => view('dashboard'))->name('ajustes');
  Route::get('/eventos/{evento}/checkout', \App\Livewire\CheckoutWizard::class)
    ->middleware(['auth'])
    ->name('eventos.checkout');
});

require __DIR__ . '/auth.php';
