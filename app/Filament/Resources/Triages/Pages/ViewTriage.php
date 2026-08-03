<?php

namespace App\Filament\Resources\Triages\Pages;

use App\Filament\Resources\Triages\TriageResource;
use Filament\Actions\Action;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Models\Doctors;
use App\Models\IPD; 
use App\Models\Laboratory;
class ViewTriage extends ViewRecord
{
    protected static string $resource = TriageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->url(TriageResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),

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

                
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
     private function generateEncounterId(string $prefix, string $modelClass): string
    {
        do {
            $encounterId = $prefix . '-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while ($modelClass::query()->where('encounter_id', $encounterId)->exists());

        return $encounterId;
    }
}
