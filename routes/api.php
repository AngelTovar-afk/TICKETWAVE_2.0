<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VenueController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TicketTypeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;

// ── Venues ──────────────────────────────────────
Route::get('/venues',      [VenueController::class, 'index']);
Route::get('/venues/{id}', [VenueController::class, 'show']);

// ── Events ──────────────────────────────────────
Route::get('/events',                        [EventController::class, 'index']);
Route::get('/events/{id}',                   [EventController::class, 'show']);
Route::get('/events/category/{category}',    [EventController::class, 'byCategory']);

// ── Ticket Types ─────────────────────────────────
Route::get('/ticket-types',                  [TicketTypeController::class, 'index']);
Route::get('/ticket-types/{id}',             [TicketTypeController::class, 'show']);
Route::get('/events/{eventId}/ticket-types', [TicketTypeController::class, 'byEvent']);

// ── Orders ───────────────────────────────────────
Route::get('/orders',      [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

// ── Users ────────────────────────────────────────
Route::get('/users',      [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
