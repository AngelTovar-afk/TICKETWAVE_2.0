<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketType;

class TicketTypeController extends Controller
{
  // GET /api/ticket-types → todos los tipos de boleto con su evento
  public function index()
  {
    $tickets = TicketType::with('event')
      ->orderBy('price')
      ->get();

    return response()->json([
      'status' => 'success',
      'total'  => $tickets->count(),
      'data'   => $tickets
    ]);
  }

  // GET /api/ticket-types/{id}
  public function show($id)
  {
    $ticket = TicketType::with('event.venue')->find($id);

    if (!$ticket) {
      return response()->json([
        'status'  => 'error',
        'message' => 'Tipo de boleto no encontrado'
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'data'   => $ticket
    ]);
  }

  // GET /api/events/{eventId}/ticket-types → boletos de un evento
  public function byEvent($eventId)
  {
    $tickets = TicketType::where('event_id', $eventId)
      ->orderBy('price')
      ->get();

    return response()->json([
      'status'   => 'success',
      'event_id' => $eventId,
      'total'    => $tickets->count(),
      'data'     => $tickets
    ]);
  }
}
