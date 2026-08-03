<?php

namespace App\Filament\Resources\Patients;

use App\Filament\Resources\Patients\Pages\CreatePatients;
use App\Filament\Resources\Patients\Pages\EditPatients;
use App\Filament\Resources\Patients\Pages\ListPatients;
use App\Filament\Resources\Patients\Pages\ViewPatients;
use App\Filament\Resources\Patients\Schemas\PatientsForm;
use App\Filament\Resources\Patients\Schemas\PatientsInfolist;
use App\Filament\Resources\Patients\Tables\PatientsTable;
use App\Models\Patients;
use App\Support\PatientPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PatientsResource extends Resource
{
    protected static ?string $model = Patients::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return PatientsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PatientsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'My Profile' : 'Patients';
    }

    public static function getModelLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'profile' : 'patient';
    }

    public static function getPluralModelLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'my profile' : 'patients';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (PatientPortal::isPatientUser()) {
            return $query->whereKey(PatientPortal::currentPatientId());
        }

        return $query;
    }

    public static function canCreate(): bool
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
            'index' => ListPatients::route('/'),
            'create' => CreatePatients::route('/create'),
            'view' => ViewPatients::route('/{record}'),
            'edit' => EditPatients::route('/{record}/edit'),
        ];
    }
}
