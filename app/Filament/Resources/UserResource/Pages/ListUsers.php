<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
  protected static string $resource = UserResource::class;

  protected function getHeaderActions(): array
  {
    return [
      CreateAction::make()
        ->label('Tambah Pengguna')
        ->icon('heroicon-o-user-plus'),
    ];
  }

  protected function getHeaderWidgets(): array
  {
    return [
      \App\Filament\Resources\UserResource\Widgets\UserStatsOverview::class,
      \App\Filament\Resources\UserResource\Widgets\UserRoleChart::class,
    ];
  }

  public function getColumns(): int | string | array
  {
      return [
          'default' => 1,
          'sm' => 2,
          'lg' => 12, // Kita gunakan sistem 12 kolom agar lebih fleksibel
      ];
  }
}
