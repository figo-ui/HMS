<?php

namespace App\Filament\Resources\Nurses;

use App\Filament\Resources\Nurses\Pages\CreateNurse;
use App\Filament\Resources\Nurses\Pages\EditNurse;
use App\Filament\Resources\Nurses\Pages\ListNurses;
use App\Filament\Resources\Nurses\Pages\ViewNurse;
use App\Filament\Resources\Nurses\Schemas\NurseForm;
use App\Filament\Resources\Nurses\Schemas\NurseInfolist;
use App\Filament\Resources\Nurses\Tables\NursesTable;
use App\Models\Nurse;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class NurseResource extends Resource
{
    protected static ?string $model = Nurse::class;

    protected static ?string $navigationParentItem = 'Staff Management';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return NurseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NurseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NursesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNurses::route('/'),
            'create' => CreateNurse::route('/create'),
            'view' => ViewNurse::route('/{record}'),
            'edit' => EditNurse::route('/{record}/edit'),
        ];
    }
}
