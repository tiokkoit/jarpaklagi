<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
  protected static string $resource = UserResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    // If password is not provided, use default password
    if (empty($data['password'])) {
      $data['password'] = Hash::make(UserResource::DEFAULT_PASSWORD);
    }

    return $data;
  }

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }

  protected function getCreatedNotificationTitle(): ?string
  {
    return 'User berhasil dibuat! Password default: password123';
  }
}
