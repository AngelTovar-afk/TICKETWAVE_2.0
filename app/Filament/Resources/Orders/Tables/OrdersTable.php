<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('user.name')
          ->label('Usuario')
          ->sortable()
          ->searchable(),
        TextColumn::make('status')
          ->label('Estado')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'pending'   => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            default     => 'gray',
          }),
        TextColumn::make('total_amount')
          ->label('Monto Total')
          ->money('MXN')
          ->sortable(),
        TextColumn::make('payment.payment_method')
          ->label('Método de Pago')
          ->default('-'),
        TextColumn::make('created_at')
          ->label('Fecha')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
        SelectFilter::make('status')
          ->label('Estado')
          ->options([
            'pending'   => 'Pendiente',
            'confirmed' => 'Confirmado',
            'cancelled' => 'Cancelado',
          ]),
      ])
      ->recordActions([
        ViewAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([]),
      ]);
  }
}
