<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Configuración de la tabla para listar Eventos.
 *
 * Muestra nombre, recinto, categoría, estado y fecha del evento.
 * Incluye acciones de editar y eliminar con confirmación.
 */
class EventsTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')
          ->label('Nombre')
          ->searchable()
          ->sortable(),

        TextColumn::make('description')
          ->label('Descripción')
          ->limit(30)
          ->searchable(),

        TextColumn::make('venue.name')
          ->label('Recinto')
          ->sortable()
          ->searchable()
          ->icon('heroicon-o-map-pin'),

        TextColumn::make('event_date')
          ->label('Fecha de evento')
          ->dateTime('d M Y H:i')
          ->sortable(),

        TextColumn::make('user.name')
          ->label('Usuario')
          ->sortable()
          ->searchable(),

        TextColumn::make('created_at')
          ->label('Fecha de creación')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('status')
          ->label('Estado')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'published' => 'success',
            'draft'     => 'warning',
            'cancelled' => 'danger',
            default     => 'gray',
          }),
      ])
      ->defaultSort('created_at', 'desc')
      ->paginated([10, 25, 50])
      ->defaultPaginationPageOption(10)
      ->filters([
        SelectFilter::make('status')
          ->label('Estado')
          ->options([
            'published' => 'Publicado',
            'draft'     => 'Borrador',
            'cancelled' => 'Cancelado',
          ]),

        SelectFilter::make('category')
          ->label('Categoría')
          ->options([
            'concert' => 'Concierto',
            'sport'   => 'Deporte',
            'theater' => 'Teatro',
          ]),
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
        BulkActionGroup::make([
          DeleteBulkAction::make()
            ->label('Eliminar seleccionados')
            ->modalHeading('¿Eliminar registros seleccionados?')
            ->modalDescription('Esta acción no se puede deshacer. Los registros seleccionados serán eliminados permanentemente.')
            ->modalSubmitActionLabel('Sí, eliminar todos')
            ->modalCancelActionLabel('Cancelar'),
        ]),
      ])
      ->emptyStateHeading('Sin eventos')
      ->emptyStateDescription('Crea el primer evento.')
      ->emptyStateIcon('heroicon-o-calendar');
  }
}
