<?php

namespace App\Filament\Resources\ProductPackages\Pages;

use App\Filament\Resources\ProductPackages\ProductPackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductPackage extends CreateRecord
{
    protected static string $resource = ProductPackageResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
