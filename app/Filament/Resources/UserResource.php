<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
  protected static ?string $model = User::class;
  protected static ?int $navigationSort = 99;

  // Default password for new users
  public const DEFAULT_PASSWORD = 'password123';

  public static function getNavigationIcon(): string|\BackedEnum|null
  {
    return 'heroicon-o-users';
  }

  public static function getNavigationLabel(): string
  {
    return 'User Management';
  }

  public static function getNavigationGroup(): ?string
  {
    return 'Settings';
  }

  public static function form(Schema $schema): Schema
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

  public static function table(Table $table): Table
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
          ->action(function ($record) {
            $record->update([
              'password' => Hash::make(self::DEFAULT_PASSWORD),
            ]);

            Notification::make()
              ->title('Password berhasil direset')
              ->body("Password untuk {$record->name} sekarang: password123")
              ->success()
              ->duration(10000)
              ->send();
          }),

        EditAction::make(),
        DeleteAction::make(),
      ])
      ->bulkActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('created_at', 'desc');
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => ListUsers::route('/'),
      'create' => CreateUser::route('/create'),
      'edit' => EditUser::route('/{record}/edit'),
    ];
  }

  /**
   * Only Manager can access User Management
   */
  public static function canAccess(): bool
  {
    $user = auth()->user();
    return $user && $user->isManager();
  }
}
