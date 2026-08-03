<?php

namespace App\Filament\Resources\Insurances;

use App\Filament\Resources\Insurances\Pages\CreateInsurance;
use App\Filament\Resources\Insurances\Pages\EditInsurance;
use App\Filament\Resources\Insurances\Pages\ListInsurances;
use App\Filament\Resources\Insurances\Pages\ViewInsurance;
use App\Filament\Resources\Insurances\Schemas\InsuranceForm;
use App\Filament\Resources\Insurances\Schemas\InsuranceInfolist;
use App\Filament\Resources\Insurances\Tables\InsurancesTable;
use App\Models\Insurance;
use App\Support\PatientPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InsuranceResource extends Resource
{
    protected static ?string $model = Insurance::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return InsuranceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InsuranceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InsurancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'My Insurance' : 'Insurances';
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
            'index' => ListInsurances::route('/'),
            'create' => CreateInsurance::route('/create'),
            'view' => ViewInsurance::route('/{record}'),
            'edit' => EditInsurance::route('/{record}/edit'),
        ];
    }
}
