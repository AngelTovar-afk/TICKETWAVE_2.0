<?php

namespace App\Filament\Resources\TicketTypes\Tables;


use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TicketTypes\Exporters\TicketTypeExporter;
use Filament\Actions\ExportAction;

/**
 * Configuración de la tabla para listar Ticket Types.
 *
 * Muestra precio, cantidades, stock restante con badge y estado de venta.
 * Usa recordActions() y toolbarActions() — API de Filament v3.2+.
 */
class TicketTypesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')
          ->label('Nombre')
          ->searchable()
          ->sortable(),

        TextColumn::make('event.name')
          ->label('Evento')
          ->sortable()
          ->searchable()
          ->icon('heroicon-o-calendar'),

        TextColumn::make('price')
          ->label('Precio')
          ->money('MXN')
          ->sortable()
          ->alignment('right'),

        TextColumn::make('quantity_available')
          ->label('Disponibles')
          ->sortable()
          ->alignment('center'),

        TextColumn::make('quantity_sold')
          ->label('Vendidos')
          ->sortable()
          ->alignment('center')
          ->color('warning'),

        TextColumn::make('stock_remaining')
          ->label('Stock restante')
          ->sortable()
          ->alignment('center')
          ->badge()
          ->color(fn(int $state): string => match (true) {
            $state > 10 => 'success',
            $state > 0  => 'warning',
            default     => 'danger',
          }),

        IconColumn::make('has_stock')
          ->label('En venta')
          ->boolean()
          ->alignment('center'),

        TextColumn::make('created_at')
          ->label('Creado')
          ->dateTime('d/m/Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        SelectFilter::make('event')
          ->relationship('event', 'name')
          ->searchable()
          ->preload()
          ->label('Filtrar por evento'),

        Filter::make('in_stock')
          ->label('Solo con stock')
          ->query(fn(Builder $query): Builder => $query->whereColumn('quantity_available', '>', 'quantity_sold')),
      ])
      ->recordActions([
        EditAction::make()->label('Editar'),
        DeleteAction::make()
          ->label('Eliminar')
          ->modalHeading('¿Estás seguro?')
          ->modalDescription('Esta acción no se puede deshacer. El registro será eliminado permanentemente.')
          ->modalSubmitActionLabel('Sí, eliminar')
          ->modalCancelActionLabel('Cancelar'),
      ])
      ->toolbarActions([
        ExportAction::make()
          ->label('Exportar')
          ->exporter(TicketTypeExporter::class),
        BulkActionGroup::make([
          DeleteBulkAction::make()
            ->label('Eliminar seleccionados')
            ->modalHeading('¿Eliminar registros seleccionados?')
            ->modalDescription('Esta acción no se puede deshacer. Los registros seleccionados serán eliminados permanentemente.')
            ->modalSubmitActionLabel('Sí, eliminar todos')
            ->modalCancelActionLabel('Cancelar'),
        ]),
      ])
      ->emptyStateHeading('Sin tipos de entrada')
      ->emptyStateDescription('Crea el primer tipo de entrada para un evento.')
      ->emptyStateIcon('heroicon-o-ticket');
  }
}
