<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $stats = Cache::remember('dashboard_stats', 60, function () {
            return [
                'total_events'  => Event::count(),
                'paid_orders'   => Order::where('status', 'confirmed')->count(),
                'total_revenue' => Order::where('status', 'confirmed')->sum('total_amount'),
                'total_users'   => User::count(),
            ];
        });

        return [
            Stat::make('Total de eventos', $stats['total_events'])
                ->description('Eventos registrados')
                ->icon('heroicon-o-calendar'),

            Stat::make('Pedidos pagados', $stats['paid_orders'])
                ->description('Órdenes confirmadas')
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Ingresos totales', '$' . number_format($stats['total_revenue'], 2))
                ->description('De pedidos confirmados')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Usuarios registrados', $stats['total_users'])
                ->description('Compradores y organizadores')
                ->icon('heroicon-o-users'),
        ];
    }
}