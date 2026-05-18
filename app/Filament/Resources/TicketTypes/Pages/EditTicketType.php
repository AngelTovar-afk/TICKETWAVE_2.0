<?php

namespace App\Filament\Resources\TicketTypes\Pages;

use App\Filament\Resources\TicketTypes\TicketTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTicketType extends EditRecord
{
  protected static string $resource = TicketTypeResource::class;

  protected function getHeaderActions(): array
  {
    return [
      DeleteAction::make(),
    ];
  }
  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }
  protected function getSavedNotificationTitle(): ?string
  {
    return 'Tipo de entrada actualizado correctamente';
  }
}
