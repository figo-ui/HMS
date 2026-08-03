<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static bool $canCreateAnother = false;
protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
    protected static string $resource = DepartmentResource::class;
}
