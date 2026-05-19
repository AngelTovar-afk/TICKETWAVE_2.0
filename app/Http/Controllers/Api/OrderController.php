<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
  // GET /api/orders → todas las órdenes con usuario e items
  public function index()
  {
    $orders = Order::with(['user', 'items.ticketType.event'])
      ->orderByDesc('created_at')
      ->get();

    return response()->json([
      'status' => 'success',
      'total'  => $orders->count(),
      'data'   => $orders
    ]);
  }

  // GET /api/orders/{id}
  public function show($id)
  {
    $order = Order::with(['user', 'items.ticketType.event'])->find($id);

    if (!$order) {
      return response()->json([
        'status'  => 'error',
        'message' => 'Orden no encontrada'
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'data'   => $order
    ]);
  }
}
