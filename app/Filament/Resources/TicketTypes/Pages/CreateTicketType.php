<?php

namespace App\Filament\Resources\TicketTypes\Pages;

use App\Filament\Resources\TicketTypes\TicketTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketType extends CreateRecord
{
  protected static string $resource = TicketTypeResource::class;
  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }
  protected function getCreatedNotificationTitle(): ?string
  {
    return 'Tipo de entrada creado correctamente';
  }
}
