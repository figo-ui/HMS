<?php

namespace App\Filament\Resources\Doctors;

use App\Filament\Resources\Doctors\Pages\CreateDoctors;
use App\Filament\Resources\Doctors\Pages\EditDoctors;
use App\Filament\Resources\Doctors\Pages\ListDoctors;
use App\Filament\Resources\Doctors\Pages\ViewDoctors;
use App\Filament\Resources\Doctors\Schemas\DoctorsForm;
use App\Filament\Resources\Doctors\Schemas\DoctorsInfolist;
use App\Filament\Resources\Doctors\Tables\DoctorsTable;
use App\Models\Doctors;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DoctorsResource extends Resource
{
    protected static ?string $model = Doctors::class;

    protected static ?string $navigationParentItem = 'Staff Management';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return DoctorsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DoctorsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoctorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDoctors::route('/'),
            'create' => CreateDoctors::route('/create'),
            'view' => ViewDoctors::route('/{record}'),
            'edit' => EditDoctors::route('/{record}/edit'),
        ];
    }
}
