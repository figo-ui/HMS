<?php

namespace App\Filament\Resources\Triages\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Doctors;
use App\Models\Laboratory;
use App\Models\OPD;
use App\Models\IPD;
use App\Models\Radiology;
use App\Models\Triage;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Str;


class TriagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('arrival_flag')
                    ->label('Arrival')
                    ->state(fn (Triage $record): string => $record->created_at?->isToday() ? 'New Today' : 'Old')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'New Today' ? 'success' : 'gray'),
                TextColumn::make('triage_id')
                    ->searchable(),
                TextColumn::make('patient.patient_id')
                    ->label('Patient ID')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable(),
                TextColumn::make('priority')
                    ->badge(),
                TextColumn::make('chief_complaint')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('nurse.full_name')
                    ->label('Nurse')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::relationship('nurse', 'full_name', 'Nurse'),
                TableFilters::select('priority', [
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'critical' => 'Critical',
                ]),
                TableFilters::select('status', [
                    'waiting' => 'Waiting',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'sent_to_opd' => 'Sent to OPD',
                ]),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                Action::make('send_to_lab')
                    ->label('Send to Lab')
                    ->icon('heroicon-o-beaker')
                    ->color('info')
                    ->visible(fn (Triage $record): bool => !in_array($record->status, ['completed', 'sent_to_opd']))
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
                    ->action(function (Triage $record, array $data): void {
                        $labId = 'LAB-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

                        Laboratory::create([
                            'lab_id' => $labId,
                            'patient_id' => $record->patient_id,
                            'doctor_id' => $data['doctor_id'],
                            'test_name' => 'Triage Requested Panel',
                            'test_type' => 'General',
                            'sample_type' => 'Blood',
                            'test_date' => now()->toDateString(),
                            'result_status' => 'pending',
                            'cost' => 0,
                            'status' => 'ordered',
                        ]);

                        $record->update([
                            'status' => 'completed',
                        ]);

                        $labUsers = User::role('lab_technician')->get();
                        if ($labUsers->isNotEmpty()) {
                            Notification::make()
                                ->title('New Laboratory Queue')
                                ->body("Triage patient {$record->patient?->full_name} sent to Lab.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($labUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to Laboratory.')
                            ->body("Lab ID: {$labId}")
                            ->success()
                            ->send();
                    }),
            
                Action::make('send_to_opd')
                    ->label('Send to OPD')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('warning')
                    ->visible(fn (Triage $record): bool => !in_array($record->status, ['completed', 'sent_to_opd']))
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
                    ->action(function (Triage $record, array $data): void {
                        $existingOpenOpd = OPD::query()
                            ->where('patient_id', $record->patient_id)
                            ->where('status', 'open')
                            ->exists();

                        if ($existingOpenOpd) {
                            Notification::make()
                                ->title('Patient already has an open OPD encounter.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $encounterId = 'OPD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

                        OPD::create([
                            'encounter_id' => $encounterId,
                            'patient_id' => $record->patient_id,
                            'type' => 'OPD',
                            'doctor_id' => $data['doctor_id'],
                            'status' => 'open',
                        ]);

                        $record->update([
                            'status' => 'completed',
                            'encounter_id' => $encounterId,
                        ]);

                        $opdUsers = User::role('opd_staff')->get();
                        if ($opdUsers->isNotEmpty()) {
                            Notification::make()
                                ->title('New OPD Queue')
                                ->body("Triage patient {$record->patient?->full_name} sent to OPD.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($opdUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to OPD successfully.')
                            ->body("Encounter ID: {$encounterId}")
                            ->success()
                            ->send();
                    }),
                   
                    

                Action::make('send_to_ipd')
                    ->label('Send to IPD')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('warning')
                    ->visible(fn (Triage $record): bool => !in_array($record->status, ['completed', 'sent_to_opd']))
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
                     ->action(function (Triage $record, array $data): void {
                        $existingAdmission = IPD::query()
                            ->where('patient_id', $record->patient_id)
                            ->whereIn('status', ['open', 'admitted'])
                            ->exists();

                        if ( $existingAdmission) {
                            Notification::make()
                                ->title('Patient already has an open IPD encounter.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $encounterId = 'IPD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

                        IPD::create([
                            'encounter_id' => $encounterId,
                            'patient_id' => $record->patient_id,
                            'type' => 'IPD',
                            'doctor_id' => $data['doctor_id'],
                            'status' => 'admitted',
                        ]);

                        $record->update([
                            'status' => 'completed',
                            'encounter_id' => $encounterId,
                        ]);

                        $ipdUsers = User::role('ipd_staff')->get();
                        if ($ipdUsers->isNotEmpty()) {
                            Notification::make()
                                ->title('New IPD Queue')
                                ->body("Triage patient {$record->patient?->full_name} sent to IPD.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($ipdUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to IPD successfully.')
                            ->body("Encounter ID: {$encounterId}")
                            ->success()
                            ->send();
                     }),
                ViewAction::make(),
                EditAction::make(),
                 DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
   
}
