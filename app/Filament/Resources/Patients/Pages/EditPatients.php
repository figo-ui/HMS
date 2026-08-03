<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientsResource;
use App\Models\Doctors;
use App\Models\IPD;
use App\Models\OPD;
use App\Models\Triage;
use App\Models\User;
use App\Support\PatientPortal;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use App\Filament\Pages\PatientPortal as PatientPortalPage;

class EditPatients extends EditRecord
{
    protected static bool $canCreateAnother=false;    protected static string $resource = PatientsResource::class;
   protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
    protected function getHeaderActions(): array
    {
        if (PatientPortal::isPatientUser()) {
            return [
                ViewAction::make(),
                Action::make('my_portal')
                    ->label('My Portal')
                    ->url(PatientPortalPage::getUrl())
                    ->color('gray')
                    ->icon('heroicon-o-arrow-left'),
            ];
        }

        return [
            Action::make('send_to_opd')
                ->label('Send to OPD')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->form([
                    Select::make('doctor_id')
                        ->label('Doctor')
                        ->options(
                            Doctors::query()
                                ->where('status', 'active')
                                ->pluck('full_name', 'id')
                                ->toArray()
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $encounterId = $this->generateEncounterId('OPD', OPD::class);

                    OPD::create([
                        'encounter_id' => $encounterId,
                        'patient_id' => $this->record->id,
                        'type' => 'OPD',
                        'doctor_id' => $data['doctor_id'],
                        'status' => 'open',
                    ]);
                    $this->record->update([
                        'status' => 'today',
                    ]);

                    $opdUsers = User::role('opd_staff')->get();
                    if ($opdUsers->isNotEmpty()) {
                        Notification::make()
                            ->title('New OPD Queue')
                            ->body("Patient {$this->record->full_name} sent to OPD.")
                            ->icon('heroicon-o-bell-alert')
                            ->sendToDatabase($opdUsers);
                    }

                    Notification::make()
                        ->title('Patient sent to OPD successfully.')
                        ->body("Encounter ID: {$encounterId}")
                        ->success()
                        ->send();
                }),
            Action::make('send_to_triage')
                ->label('Send to Triage')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('warning')
                ->action(function (): void {
                    $pendingTriageExists = Triage::query()
                        ->where('patient_id', $this->record->id)
                        ->whereIn('status', ['waiting', 'in_progress', 'completed'])
                        ->exists();

                    if ($pendingTriageExists) {
                        Notification::make()
                            ->title('Patient already exists in triage queue.')
                            ->warning()
                            ->send();

                        return;
                    }

                    $triageId = $this->generateEncounterId('TRG', Triage::class);

                    Triage::create([
                        'triage_id' => $triageId,
                        'patient_id' => $this->record->id,
                        'priority' => 'medium',
                        'status' => 'waiting',
                    ]);

                    $this->record->update([
                        'status' => 'today',
                    ]);

                    $triageUsers = User::role('triage_nurse')->get();
                    if ($triageUsers->isNotEmpty()) {
                        Notification::make()
                            ->title('New Triage Queue')
                            ->body("Patient {$this->record->full_name} sent to Triage.")
                            ->icon('heroicon-o-bell-alert')
                            ->sendToDatabase($triageUsers);
                    }

                    Notification::make()
                        ->title('Patient sent to Triage successfully.')
                        ->body("Triage ID: {$triageId}")
                        ->success()
                        ->send();
                }),
            Action::make('admit_to_ipd')
                ->label('Admit to IPD')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('warning')
                ->form([
                    Select::make('doctor_id')
                        ->label('Doctor')
                        ->options(
                            Doctors::query()
                                ->where('status', 'active')
                                ->pluck('full_name', 'id')
                                ->toArray()
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $encounterId = $this->generateEncounterId('IPD', IPD::class);

                    IPD::create([
                        'encounter_id' => $encounterId,
                        'patient_id' => $this->record->id,
                        'type' => 'IPD',
                        'doctor_id' => $data['doctor_id'],
                        'status' => 'admitted',
                    ]);

                    Notification::make()
                        ->title('Patient admitted to IPD successfully.')
                        ->body("Encounter ID: {$encounterId}")
                        ->success()
                        ->send();
                }),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $user = $this->record->author;

        if ($user) {
            $user->update([
                'name' => $this->record->full_name,
                'email' => $this->record->email,
            ]);
        }
    }

    private function generateEncounterId(string $prefix, string $modelClass): string
    {
        do {
            $encounterId = $prefix . '-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while ($modelClass::query()->where('encounter_id', $encounterId)->exists());

        return $encounterId;
    }
}
