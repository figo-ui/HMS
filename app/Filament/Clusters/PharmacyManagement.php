<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class PharmacyManagement extends Cluster
{
    protected static ?string $navigationLabel = 'Pharmacy Management';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
}
