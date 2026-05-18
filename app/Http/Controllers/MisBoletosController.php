<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Controlador para la página "Mis Boletos" del comprador.
 * Muestra el historial de pedidos del usuario autenticado.
 */
class MisBoletosController extends Controller
{
    /**
     * Muestra los pedidos del usuario autenticado.
     * Eager loading de orderItems, ticketType y event para evitar N+1 queries.
     * Ordenados por fecha descendente.
     */
    public function index()
    {
        $orders = Order::with(['orderItems.ticketType.event'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $boletosActivos = $orders->where('status', 'pending')
            ->sum(fn($order) => $order->orderItems->sum('quantity'));

        $totalGastado = $orders->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        return view('mis-boletos', compact('orders', 'boletosActivos', 'totalGastado'));
    }
}
