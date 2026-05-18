<?php

namespace App\Filament\Resources\Venues\Tables;

use App\Models\Venue;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Configuración de la tabla para listar Recintos.
 *
 * Muestra nombre, ciudad, estado, capacidad y estado activo.
 * Incluye acciones de editar y eliminar con confirmación.
 * Protege la eliminación si el recinto tiene eventos asociados.
 */
class VenuesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')
          ->label('Nombre')
          ->searchable()
          ->sortable(),

        TextColumn::make('city')
          ->label('Ciudad')
          ->searchable()
          ->sortable(),

        TextColumn::make('state')
          ->label('Estado')
          ->searchable(),

        TextColumn::make('country')
          ->label('País')
          ->searchable(),

        TextColumn::make('capacity')
          ->label('Capacidad')
          ->numeric()
          ->sortable(),

        IconColumn::make('active')
          ->label('Activo')
          ->boolean(),

        TextColumn::make('created_at')
          ->label('Creado')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        SelectFilter::make('active')
          ->label('Estado')
          ->options([
            '1' => 'Activo',
            '0' => 'Inactivo',
          ]),
      ])
      ->recordActions([
        EditAction::make()->label('Editar'),
        DeleteAction::make()
          ->label('Eliminar')
          ->modalHeading('¿Estás seguro?')
          ->modalDescription('Esta acción no se puede deshacer. El registro será eliminado permanentemente.')
          ->modalSubmitActionLabel('Sí, eliminar')
          ->modalCancelActionLabel('Cancelar')
          ->before(function (Venue $record, $action) {
            if ($record->events()->exists()) {
              Notification::make()
                ->title('No se puede eliminar')
                ->body('Este recinto tiene eventos asociados.')
                ->danger()
                ->send();
              $action->cancel();
            }
          }),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make()
            ->label('Eliminar seleccionados')
            ->modalHeading('¿Eliminar registros seleccionados?')
            ->modalDescription('Esta acción no se puede deshacer. Los registros seleccionados serán eliminados permanentemente.')
            ->modalSubmitActionLabel('Sí, eliminar todos')
            ->modalCancelActionLabel('Cancelar')
            ->before(function ($records, $action) {
              foreach ($records as $record) {
                if ($record->events()->exists()) {
                  Notification::make()
                    ->title('No se puede eliminar')
                    ->body("El recinto '{$record->name}' tiene eventos asociados.")
                    ->danger()
                    ->send();
                  $action->cancel();
                  return;
                }
              }
            }),
        ]),
      ])
      ->emptyStateHeading('Sin recintos')
      ->emptyStateDescription('Crea el primer recinto.')
      ->emptyStateIcon('heroicon-o-map-pin');
  }
}
