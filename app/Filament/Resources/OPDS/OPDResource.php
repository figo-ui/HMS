<?php

namespace App\Filament\Resources\OPDS;

use App\Filament\Resources\OPDS\Pages\EditOPD;
use App\Filament\Resources\OPDS\Pages\ListOPDS;
use App\Filament\Resources\OPDS\Pages\ViewOPD;
use App\Filament\Resources\OPDS\Schemas\OPDForm;
use App\Filament\Resources\OPDS\Schemas\OPDInfolist;
use App\Filament\Resources\OPDS\Tables\OPDSTable;
use App\Models\OPD;
use App\Support\PatientPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OPDResource extends Resource
{
    protected static ?string $model = OPD::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return OPDForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OPDInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OPDSTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'My OPD Visits' : 'OPDS';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (PatientPortal::isPatientUser()) {
            return $query->where('patient_id', PatientPortal::currentPatientId());
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return ! PatientPortal::isPatientUser();
    }

    public static function canEdit($record): bool
    {
        return ! PatientPortal::isPatientUser();
    }

    public static function canDelete($record): bool
    {
        return ! PatientPortal::isPatientUser();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOPDS::route('/'),
            'view' => ViewOPD::route('/{record}'),
            'edit' => EditOPD::route('/{record}/edit'),
        ];
    }
}
