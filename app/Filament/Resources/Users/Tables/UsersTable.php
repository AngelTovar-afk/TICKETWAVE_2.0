<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\Users\Exporters\UserExporter;
use Filament\Actions\ExportAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UsersTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')
          ->label('Nombre')
          ->searchable()
          ->sortable(),

        TextColumn::make('email')
          ->label('Correo electrónico')
          ->searchable()
          ->sortable(),

        TextColumn::make('role')
          ->label('Rol')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'admin'     => 'danger',
            'organizer' => 'warning',
            'comprador'     => 'success',
            default     => 'gray',
          })
          ->formatStateUsing(fn(string $state): string => match ($state) {
            'admin'     => 'Administrador',
            'organizer' => 'Organizador',
            'comprador'     => 'Comprador',
            default     => $state,
          })
          ->sortable(),

        TextColumn::make('email_verified_at')
          ->label('Email verificado')
          ->dateTime('d/m/Y')
          ->sortable()
          ->placeholder('Sin verificar'),

        TextColumn::make('created_at')
          ->label('Fecha de registro')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        SelectFilter::make('role')
          ->label('Filtrar por rol')
          ->options([
            'comprador'     => 'Comprador',
            'organizer' => 'Organizador',
            'admin'     => 'Administrador',
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
          ->hidden(fn($record): bool => $record->id === Auth::id())
      ])
      ->toolbarActions([
        ExportAction::make()
          ->label('Exportar')
          ->exporter(UserExporter::class),
        BulkActionGroup::make([
          DeleteBulkAction::make()
            ->label('Eliminar seleccionados')
            ->modalHeading('¿Eliminar registros seleccionados?')
            ->modalDescription('Esta acción no se puede deshacer. Los registros seleccionados serán eliminados permanentemente.')
            ->modalSubmitActionLabel('Sí, eliminar todos')
            ->modalCancelActionLabel('Cancelar')
            ->action(function (Collection $records): void {
              $records
                ->reject(fn($record) => $record->id === Auth::id())
                ->each->delete();
            }),
        ]),
      ])
      ->emptyStateHeading('Sin usuarios registrados')
      ->emptyStateDescription('Aún no hay usuarios en la plataforma.')
      ->emptyStateIcon('heroicon-o-users');
  }
}
