<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venue;

class VenueController extends Controller
{
  // GET /api/venues → todos los venues activos
  public function index()
  {
    $venues = Venue::where('active', true)
      ->orderBy('name')
      ->get();

    return response()->json([
      'status' => 'success',
      'total'  => $venues->count(),
      'data'   => $venues
    ]);
  }

  // GET /api/venues/{id} → un venue con sus eventos
  public function show($id)
  {
    $venue = Venue::with('events')->find($id);

    if (!$venue) {
      return response()->json([
        'status'  => 'error',
        'message' => 'Venue no encontrado'
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'data'   => $venue
    ]);
  }
}
