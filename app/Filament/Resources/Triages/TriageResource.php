<?php

namespace App\Filament\Resources\Triages;

use App\Filament\Resources\Triages\Pages\CreateTriage;
use App\Filament\Resources\Triages\Pages\EditTriage;
use App\Filament\Resources\Triages\Pages\ListTriages;
use App\Filament\Resources\Triages\Pages\ViewTriage;
use App\Filament\Resources\Triages\Schemas\TriageForm;
use App\Filament\Resources\Triages\Schemas\TriageInfolist;
use App\Filament\Resources\Triages\Tables\TriagesTable;
use App\Models\Triage;
use App\Support\PatientPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TriageResource extends Resource
{
    protected static ?string $model = Triage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'triage_id';

    public static function form(Schema $schema): Schema
    {
        return TriageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TriageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TriagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'My Triage' : 'Triages';
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
            'index' => ListTriages::route('/'),
            'create' => CreateTriage::route('/create'),
            'view' => ViewTriage::route('/{record}'),
            'edit' => EditTriage::route('/{record}/edit'),
        ];
    }
}
