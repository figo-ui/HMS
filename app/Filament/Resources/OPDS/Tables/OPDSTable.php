<?php

namespace App\Filament\Resources\OPDS\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Laboratory;
use App\Models\OPD;
use App\Models\PatientHistory;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctors;
use Filament\Forms\Components\Select;
use App\Models\Radiology;
class OPDSTable
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
                    ->state(fn (OPD $record): string => $record->created_at?->isToday() ? 'New Today' : 'Old')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'New Today' ? 'success' : 'gray'),
                TextColumn::make('encounter_id')
                    ->searchable(),
                TextColumn::make('patient.patient_id')
                    ->label('Patient ID')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->label('Patient Name')
                    ->searchable(),
                TextColumn::make('patient.phone')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('doctor.full_name')
                    ->label('Doctor')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('icd10_code')
                    ->label('ICD-10')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('admission_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('discharge_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('follow_up_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bed.bed_id')
                    ->label('Bed')
                    ->searchable(),
                TextColumn::make('prescription_id')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'New Patient',
                        'closed' => 'Closed',
                        'transferred' => 'Transferred',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'closed' => 'success',
                        'transferred' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::relationship('doctor', 'full_name', 'Doctor'),
                TableFilters::distinct('department', OPD::class, 'Department'),
                TableFilters::distinct('bed_id', OPD::class, 'Bed'),
                TableFilters::select('type', [
                    'OPD' => 'OPD',
                    'IPD' => 'IPD',
                ]),
                TableFilters::select('status', [
                    'open' => 'Open',
                    'closed' => 'Closed',
                    'transferred' => 'Transferred',
                ]),
                TableFilters::dateRange('admission_date', 'Admission Date'),
                TableFilters::dateRange('discharge_date', 'Discharge Date'),
                TableFilters::dateRange('follow_up_date', 'Follow-up Date'),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                Action::make('call_patient')
                    ->label('Call')
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->url(fn (OPD $record): ?string => filled($record->patient?->phone) ? 'tel:' . $record->patient->phone : null)
                    ->visible(fn (OPD $record): bool => filled($record->patient?->phone)),
                Action::make('send_to_lab')
                    ->label('Send to Lab')
                    ->icon('heroicon-o-beaker')
                    ->color('warning')
                    ->visible(fn (OPD $record): bool => filled($record->patient_id) && filled($record->doctor_id))
                    ->form([
                        Select::make('service_id')
                            ->label('Lab Service')
                            ->options(fn (): array => Service::query()
                                ->where('service_type', 'lab')
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('test_name')
                            ->label('Test / Panel')
                            ->default('Initial OPD Lab Panel')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (OPD $record, array $data): void {
                        $pendingLabExists = Laboratory::query()
                            ->where('patient_id', $record->patient_id)
                            ->where('doctor_id', $record->doctor_id)
                            ->where('encounter_id', $record->encounter_id)
                            ->whereIn('status', ['ordered', 'sample_collected', 'processing'])
                            ->exists();

                        if ($pendingLabExists) {
                            Notification::make()
                                ->title('Lab request already exists for this patient.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $service = Service::find($data['service_id']);

                        if (! $service) {
                            Notification::make()
                                ->title('Selected lab service was not found.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $labId = 'LAB-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
                        $serviceRequest = ServiceRequest::create([
                            'request_number' => 'SR-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                            'patient_id' => $record->patient_id,
                            'encounter_type' => 'OPD',
                            'encounter_id' => $record->encounter_id,
                            'service_id' => $service->id,
                            'requested_by' => Auth::id(),
                            'payment_status' => 'pending',
                            'fulfillment_status' => 'requested',
                            'total_amount' => $service->price,
                            'patient_share' => $service->calculatePatientShare($record->patient),
                            'insurance_share' => $service->calculateInsuranceShare($record->patient),
                            'discount' => 0,
                            'paid_amount' => 0,
                            'requested_at' => now(),
                            'notes' => 'OPD laboratory request. Patient must clear payment before sample processing.',
                        ]);

                        Laboratory::create([
                            'lab_id' => $labId,
                            'patient_id' => $record->patient_id,
                            'doctor_id' => $record->doctor_id,
                            'service_request_id' => $serviceRequest->id,
                            'encounter_type' => 'OPD',
                            'encounter_id' => $record->encounter_id,
                            'test_name' => $data['test_name'],
                            'test_type' => 'General',
                            'sample_type' => 'Blood',
                            'test_date' => now()->toDateString(),
                            'result_status' => 'pending',
                            'cost' => $service->price,
                            'status' => 'ordered',
                        ]);

                        $labUsers = User::role('lab_technician')->get();
                        if ($labUsers->isNotEmpty()) {
                            Notification::make()
                                ->title('New Laboratory Queue')
                                ->body("OPD patient {$record->patient?->full_name} sent to Lab.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($labUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to cashier for Lab payment.')
                            ->body("Lab ID: {$labId}. Request: {$serviceRequest->request_number}")
                            ->success()
                            ->send();
                    }),
                Action::make('discharge_patient')
                    ->label('Discharge')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (OPD $record): bool => $record->status === 'open')
                    ->form([
                        Textarea::make('discharge_summary')
                            ->label('Discharge Summary')
                            ->required()
                            ->rows(3),
                        DatePicker::make('follow_up_date')
                            ->label('Follow-up Date'),
                    ])
                    ->action(function (OPD $record, array $data): void {
                        $record->update([
                            'status' => 'closed',
                            'discharge_date' => now()->toDateString(),
                            'discharge_summary' => $data['discharge_summary'],
                            'follow_up_date' => $data['follow_up_date'] ?? null,
                        ]);

                        if (filled($record->patient_id)) {
                            PatientHistory::create([
                                'patient_id' => $record->patient_id,
                                'encounter_id' => $record->encounter_id,
                                'source' => 'opd',
                                'activity' => 'OPD discharged',
                                'details' => $data['discharge_summary'],
                                'occurred_at' => now(),
                                'created_by' => Auth::id(),
                            ]);
                        }

                        Notification::make()
                            ->title('Patient discharged from OPD.')
                            ->success()
                            ->send();
                    }),
                
                Action::make('send_to_radiology')
                    ->label('Send to Radiology')
                    ->icon('heroicon-o-photo')
                    ->color('gray')
                    ->visible(fn (OPD $record): bool => $record->status !== 'sent_to_opd')
                    ->visible(fn (OPD $record): bool => $record->status === 'open')
                    ->form([
                        Select::make('service_id')
                            ->label('Radiology Service')
                            ->options(fn (): array => Service::query()
                                ->where('service_type', 'radiology')
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required(),
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
                        Textarea::make('exam_name')
                            ->label('Exam')
                            ->default('OPD Requested Imaging')
                            ->required()
                            ->rows(2),
                    ])
                    
                    ->action(function (OPD $record, array $data): void {
                        $service = Service::find($data['service_id']);

                        if (! $service) {
                            Notification::make()
                                ->title('Selected radiology service was not found.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $radiologyId = 'RAD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
                        $serviceRequest = ServiceRequest::create([
                            'request_number' => 'SR-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                            'patient_id' => $record->patient_id,
                            'encounter_type' => 'OPD',
                            'encounter_id' => $record->encounter_id,
                            'service_id' => $service->id,
                            'requested_by' => Auth::id(),
                            'payment_status' => 'pending',
                            'fulfillment_status' => 'requested',
                            'total_amount' => $service->price,
                            'patient_share' => $service->calculatePatientShare($record->patient),
                            'insurance_share' => $service->calculateInsuranceShare($record->patient),
                            'discount' => 0,
                            'paid_amount' => 0,
                            'requested_at' => now(),
                            'notes' => 'OPD radiology request. Patient must clear payment before imaging.',
                        ]);

                        Radiology::create([
                            'radiology_id' => $radiologyId,
                            'patient_id' => $record->patient_id,
                            'doctor_id' => $data['doctor_id'],
                            'service_request_id' => $serviceRequest->id,
                            'encounter_type' => 'OPD',
                            'encounter_id' => $record->encounter_id,
                            'exam_name' => $data['exam_name'],
                            'modality' => 'X-Ray',
                            'exam_date' => now()->toDateString(),
                            'result_status' => 'pending',
                            'status' => 'ordered',
                        ]);

                        

                        $radiologyUsers = User::role('radiology_technician')->get();
                        if ($radiologyUsers->isNotEmpty()) {
                            Notification::make()
                                ->title('New Radiology Queue')
                                ->body("OPD patient {$record->patient?->full_name} sent to Radiology.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($radiologyUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to cashier for Radiology payment.')
                            ->body("Radiology ID: {$radiologyId}. Request: {$serviceRequest->request_number}")
                            ->success()
                            ->send();
             }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
