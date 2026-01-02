<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class UserForm
{
    public static function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECTION 1: PROFIL DASAR
                Section::make('Identitas Akun')
                    ->description('Kelola nama lengkap dan alamat email resmi untuk identitas user di sistem.')
                    ->icon('heroicon-m-user-circle')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->placeholder('Contoh: Ahmad Karyawan')
                                ->required()
                                ->maxLength(255)
                                ->autofocus(),

                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->placeholder('Contoh: ahmad@gmail.com')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                        ]),
                    ]),

                // SECTION 2: AKSES & KEAMANAN
                Section::make('Hak Akses & Keamanan')
                    ->description('Tentukan peran user dan atur password untuk menjaga keamanan akses dashboard.')
                    ->icon('heroicon-m-shield-check')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('role')
                                ->label('Jabatan / Role')
                                ->options(User::getRoles())
                                ->required()
                                ->native(false)
                                ->default('admin')
                                ->helperText('Pilih peran yang sesuai dengan tanggung jawab user.'),
                            
                            TextInput::make('password')
                                ->label('Password Akses')
                                ->password()
                                ->placeholder('********')
                                ->dehydrated(fn(?string $state) => filled($state))
                                ->minLength(8)
                                ->helperText(fn(string $operation) => $operation === 'create'
                                    ? 'Default: password123 (Kosongkan jika ingin pakai default)'
                                    : 'Biarkan kosong jika tidak ingin mengubah password'),
                        ]),
                    ]),

                // SECTION 3: FOTO & STATUS
                Section::make('Foto Profil & Status')
                    ->description('Upload foto formal dan atur izin akses login user.')
                    ->icon('heroicon-m-photo')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('avatar')
                                ->label('Foto Profil')
                                ->avatar()
                                ->image()
                                ->disk('public')
                                ->directory('avatars')
                                ->imageEditor()
                                ->circleCropper()
                                ->required()
                                ->helperText('Gunakan foto formal ukuran 1:1.'),

                            Toggle::make('is_active')
                                ->label('Status Akun Aktif')
                                ->onColor('success')
                                ->offColor('danger')
                                ->default(true)
                                ->inline(false) // Supaya sejajar rapi dengan input sebelah
                                ->helperText('Nonaktifkan jika user sudah tidak bertugas di CV Agrosehat.'),
                        ]),
                    ]),
            ]);
    }
}
