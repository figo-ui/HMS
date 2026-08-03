<?php

namespace App\Filament\Resources\Laboratories;

use App\Filament\Resources\Laboratories\Pages\CreateLaboratory;
use App\Filament\Resources\Laboratories\Pages\EditLaboratory;
use App\Filament\Resources\Laboratories\Pages\ListLaboratories;
use App\Filament\Resources\Laboratories\Pages\ViewLaboratory;
use App\Filament\Resources\Laboratories\Schemas\LaboratoryForm;
use App\Filament\Resources\Laboratories\Schemas\LaboratoryInfolist;
use App\Filament\Resources\Laboratories\Tables\LaboratoriesTable;
use App\Models\Laboratory;
use App\Support\ClinicalReportAccess;
use App\Support\PatientPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LaboratoryResource extends Resource
{
    protected static ?string $model = Laboratory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return LaboratoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LaboratoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaboratoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'My Lab Requests' : 'Laboratories';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (PatientPortal::isPatientUser()) {
            return $query->where('patient_id', PatientPortal::currentPatientId());
        }

        if (ClinicalReportAccess::isDoctorUser()) {
            return $query->where('doctor_id', ClinicalReportAccess::currentDoctorId());
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
            'index' => ListLaboratories::route('/'),
            'create' => CreateLaboratory::route('/create'),
            'view' => ViewLaboratory::route('/{record}'),
            'edit' => EditLaboratory::route('/{record}/edit'),
        ];
    }
}
