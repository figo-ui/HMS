<?php

namespace App\Filament\Resources\PharmacySales;

use App\Filament\Resources\PharmacySales\Pages\ListPharmacySales;
use App\Filament\Resources\PharmacySales\Pages\ViewPharmacySale;
use App\Filament\Resources\PharmacySales\Schemas\PharmacySaleInfolist;
use App\Filament\Resources\PharmacySales\Tables\PharmacySalesTable;
use App\Models\PharmacySale;
use App\Support\PatientPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PharmacySaleResource extends Resource
{
    protected static ?string $model = PharmacySale::class;

    protected static ?string $navigationParentItem = 'Pharmacy Management';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $recordTitleAttribute = 'sale_id';

    public static function infolist(Schema $schema): Schema
    {
        return PharmacySaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PharmacySalesTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return PatientPortal::isPatientUser() ? 'My Pharmacy Sales' : 'Pharmacy Sales';
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
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return ! PatientPortal::isPatientUser();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPharmacySales::route('/'),
            'view' => ViewPharmacySale::route('/{record}'),
        ];
    }
}
