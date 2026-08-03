<?php

namespace App\Filament\Resources\Staff;

use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Filament\Resources\Payments\Pages\CreatePayments;
use App\Filament\Resources\Payments\Pages\ViewPayments;
use App\Filament\Resources\Payments\Tables\PaymentsTable;
use App\Filament\Resources\Payments\Pages\EditPayments;
use App\Filament\Resources\Payments\Schemas\PaymentsForm;
use App\Filament\Resources\Payments\Schemas\PaymentsInfolist;
use App\Models\Payments;;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PaymentsResource extends Resource
{
    protected static ?string $model = Payments::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PaymentsForm::configure($schema);
    }

    //public static function infolist(Schema $schema): Schema
   // {
   //     return PaymentsInfolist::configure($schema);
   // }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayments::route('/create'),
            'view' => ViewPayments::route('/{record}'),
            'edit' => EditPayments::route('/{record}/edit'),
        ];
    }
}