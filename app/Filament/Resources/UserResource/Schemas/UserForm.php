<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
  public static function schema(Schema $schema): Schema
  {
    return $schema
      ->columns(2)
      ->components([
        Section::make('Informasi User')
          ->description('Data dasar akun user')
          ->icon('heroicon-o-user')
          ->columns(2)
          ->schema([
            TextInput::make('name')
              ->label('Nama Lengkap')
              ->required()
              ->maxLength(255)
              ->autofocus(),

            TextInput::make('email')
              ->label('Email')
              ->email()
              ->required()
              ->unique(ignoreRecord: true)
              ->maxLength(255),

            TextInput::make('password')
              ->label('Password')
              ->password()
              ->dehydrated(fn(?string $state) => filled($state))
              ->minLength(8)
              ->helperText(fn(string $operation) => $operation === 'create'
                ? 'Default: password123 (kosongkan untuk pakai default)'
                : 'Kosongkan jika tidak ingin mengubah password'),

            Select::make('role')
              ->label('Role')
              ->options(User::getRoles())
              ->required()
              ->native(false)
              ->default('admin'),
          ]),

        Section::make('Foto Profil')
          ->schema([
            FileUpload::make('avatar')
              ->label('Foto Profil')
              ->image()
              ->avatar()
              ->disk('public')
              ->directory('avatars')
              ->maxSize(2048)
              ->imageResizeMode('cover')
              ->imageCropAspectRatio('1:1')
              ->imageResizeTargetWidth('200')
              ->imageResizeTargetHeight('200')
              ->circleCropper()
              ->columnSpanFull(),
          ]),

        Section::make('Status')
          ->schema([
            Toggle::make('is_active')
              ->label('Aktif')
              ->helperText('User yang tidak aktif tidak bisa login')
              ->default(true),
          ]),
      ]);
  }
}
