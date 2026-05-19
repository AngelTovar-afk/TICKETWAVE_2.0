<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
  // GET /api/events → solo eventos publicados con su venue
  public function index()
  {
    $events = Event::with('venue')
      ->where('status', 'published')
      ->orderBy('event_date')
      ->get();

    return response()->json([
      'status' => 'success',
      'total'  => $events->count(),
      'data'   => $events
    ]);
  }

  // GET /api/events/{id} → evento con venue y tipos de boleto
  public function show($id)
  {
    $event = Event::with(['venue', 'ticketTypes'])->find($id);

    if (!$event) {
      return response()->json([
        'status'  => 'error',
        'message' => 'Evento no encontrado'
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'data'   => $event
    ]);
  }

  // GET /api/events/category/{category}
  public function byCategory($category)
  {
    $events = Event::with('venue')
      ->where('status', 'published')
      ->where('category', $category)
      ->orderBy('event_date')
      ->get();

    return response()->json([
      'status'   => 'success',
      'category' => $category,
      'total'    => $events->count(),
      'data'     => $events
    ]);
  }
}
