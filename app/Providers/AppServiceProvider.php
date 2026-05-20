<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  public function boot(): void
  {
    // Registra el observer de Order para devolver boletos al cancelar
    Order::observe(OrderObserver::class);
    // Forzar HTTPS en producción
    if (config('app.env') === 'production') {
      URL::forceScheme('https');
    }
  }
}
