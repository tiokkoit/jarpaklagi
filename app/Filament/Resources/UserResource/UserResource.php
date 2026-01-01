<?php

namespace App\Filament\Resources\UserResource;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Schemas\UserForm;
use App\Filament\Resources\UserResource\Tables\UserTable;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
  protected static ?string $model = User::class;
  protected static ?int $navigationSort = 99;

  // Default password for new users
  public const DEFAULT_PASSWORD = 'password123';

  public static function getNavigationIcon(): string|\BackedEnum|null
  {
    return 'heroicon-o-user-group';
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
    return UserForm::schema($schema);
  }

  public static function table(Table $table): Table
  {
    return UserTable::make($table);
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
