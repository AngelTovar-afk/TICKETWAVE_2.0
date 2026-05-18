<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateUser extends CreateRecord
{
  protected static string $resource = UserResource::class;
  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }
  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['email_verified_at'] = Carbon::now();
    return $data;
  }
  protected function getCreatedNotificationTitle(): ?string
  {
    return 'Usuario creado correctamente';
  }
}
