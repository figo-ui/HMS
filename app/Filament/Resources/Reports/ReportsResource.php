<?php

namespace App\Filament\Resources\Reports;

use App\Filament\Resources\Reports\Pages\CreateReports;
use App\Filament\Resources\Reports\Pages\EditReports;
use App\Filament\Resources\Reports\Pages\ListReports;
use App\Filament\Resources\Reports\Pages\ViewReports;
use App\Filament\Resources\Reports\Schemas\ReportsForm;
use App\Filament\Resources\Reports\Schemas\ReportsInfolist;
use App\Filament\Resources\Reports\Tables\ReportsTable;
use App\Models\Reports;
use App\Support\PatientPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ReportsResource extends Resource
{
    protected static ?string $model = Reports::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ReportsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReportsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canViewAny(): bool
    {
        return ! PatientPortal::isPatientUser();
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
            'index' => ListReports::route('/'),
            'create' => CreateReports::route('/create'),
            'view' => ViewReports::route('/{record}'),
            'edit' => EditReports::route('/{record}/edit'),
        ];
    }
}
