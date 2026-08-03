<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Pages\PatientPortal as PatientPortalPage;
use App\Filament\Resources\Patients\PatientsResource;
use App\Models\Doctors;
use App\Models\IPD;
use App\Models\OPD;
use App\Models\Triage;
use App\Models\User;
use App\Support\PatientPortal;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewPatients extends ViewRecord
{
    protected static string $resource = PatientsResource::class;

    protected function getHeaderActions(): array
    {
        if (PatientPortal::isPatientUser()) {
            return [
                Action::make('back_to_portal')
                    ->label('My Portal')
                    ->url(PatientPortalPage::getUrl())
                    ->color('gray')
                    ->icon('heroicon-o-arrow-left'),
                EditAction::make(),
            ];
        }

        return [
            Action::make('back')
                ->label('Back')
                ->url(PatientsResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            EditAction::make(),
        ];
    }

    
}
