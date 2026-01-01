<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Filament\Resources\UserResource\UserResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserTable
{
  public static function make(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('avatar')
          ->label('Foto')
          ->circular()
          ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=10b981&color=fff')
          ->size(40),

        TextColumn::make('name')
          ->label('Nama')
          ->searchable()
          ->sortable()
          ->weight('bold'),

        TextColumn::make('email')
          ->label('Email')
          ->searchable()
          ->sortable()
          ->icon('heroicon-o-envelope'),

        TextColumn::make('role')
          ->label('Role')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'manager' => 'danger',
            'admin' => 'primary',
            'inventory' => 'warning',
            default => 'gray',
          })
          ->formatStateUsing(fn(string $state): string => match ($state) {
            'manager' => 'Manager',
            'admin' => 'Admin',
            'inventory' => 'Inventory',
            default => $state,
          }),

        IconColumn::make('is_active')
          ->label('Aktif')
          ->boolean()
          ->alignCenter(),

        TextColumn::make('created_at')
          ->label('Dibuat')
          ->dateTime('d M Y')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        SelectFilter::make('role')
          ->label('Role')
          ->options([
            'manager' => 'Manager',
            'admin' => 'Admin',
            'inventory' => 'Inventory',
          ]),
      ])
      ->actions([
        // Reset Password Action
        Action::make('resetPassword')
          ->label('Reset Password')
          ->icon('heroicon-o-key')
          ->color('warning')
          ->requiresConfirmation()
          ->modalHeading('Reset Password')
          ->modalDescription(fn($record) => "Reset password untuk {$record->name}? Password baru akan menjadi: password123")
          ->modalSubmitActionLabel('Ya, Reset Password')
          ->modalCancelActionLabel('Batal')
          ->action(function ($record) {
            $record->update([
              'password' => Hash::make(UserResource::DEFAULT_PASSWORD),
            ]);

            Notification::make()
              ->title('Password berhasil direset')
              ->body("Password untuk {$record->name} sekarang: password123")
              ->success()
              ->duration(10000)
              ->send();
          }),

        EditAction::make(),
        DeleteAction::make()
        ->modalHeading('Hapus Pengguna')
        ->modalDescription(fn($record) => "Apakah Anda yakin ingin menghapus data {$record->name}? Tindakan ini tidak dapat dibatalkan.")
        ->modalSubmitActionLabel('Ya, Hapus')
        ->modalCancelActionLabel('Batal')
        ->successNotification(
          Notification::make()
              ->success()
              ->title('Pengguna Berhasil Dihapus')
              ->body('Data pengguna telah dihapus secara permanen dari sistem.')
      ),
      ])
      ->recordUrl(null)
      ->defaultSort('created_at', 'desc');
  }
}
