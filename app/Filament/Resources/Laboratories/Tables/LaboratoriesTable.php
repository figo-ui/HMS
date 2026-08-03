<?php

namespace App\Filament\Resources\Laboratories\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Laboratory;
use App\Models\OPD;
use App\Models\IPD;
use App\Models\Prescription;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Support\ClinicalReportAccess;
use Filament\Actions\Action;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LaboratoriesTable
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
                    ->state(fn (Laboratory $record): string => $record->created_at?->isToday() ? 'New Today' : 'Old')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'New Today' ? 'success' : 'gray'),
                TextColumn::make('test_date')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->since(),
                TextColumn::make('lab_id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('patient.id')
                    ->label('Patient ID')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable(),
                TextColumn::make('doctor.full_name')
                    ->label('Doctor')
                    ->searchable(),
                TextColumn::make('test_name')
                    ->searchable(),
                TextColumn::make('test_type')
                    ->searchable(),
                TextColumn::make('sample_type')
                    ->searchable(),
                TextColumn::make('test_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('result_date')
                    ->date()
                    ->sortable()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TextColumn::make('result_status')
                    ->badge()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TextColumn::make('normal_range')
                    ->searchable()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('serviceRequest.payment_status')
                    ->label('Payment')
                    ->badge()
                    ->default('not sent')
                    ->color(fn (?string $state): string => match ($state) {
                        'paid', 'insurance', 'waived' => 'success',
                        'verified' => 'info',
                        'cancelled' => 'danger',
                        null, 'not sent' => 'gray',
                        default => 'warning',
                    }),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('report_path')
                    ->label('Lab Report')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'View' : '-')
                    ->url(fn (Laboratory $record): ?string => filled($record->report_path)
                        ? Storage::disk('public')->url($record->report_path)
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
            ])
            ->filters([
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::relationship('doctor', 'full_name', 'Doctor'),
                TableFilters::distinct('test_name', Laboratory::class, 'Test Name'),
                TableFilters::distinct('test_type', Laboratory::class, 'Test Type'),
                TableFilters::distinct('sample_type', Laboratory::class, 'Sample Type'),
                TableFilters::select('result_status', [
                    'normal' => 'Normal',
                    'abnormal' => 'Abnormal',
                    'critical' => 'Critical',
                    'pending' => 'Pending',
                ])->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TableFilters::select('status', [
                    'ordered' => 'Ordered',
                    'sample_collected' => 'Sample collected',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
                TableFilters::dateRange('test_date', 'Test Date'),
                TableFilters::dateRange('result_date', 'Result Date')
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TableFilters::createdAt(),
            ],
            layout: FiltersLayout::AboveContentCollapsible)



            ->recordActions([
                Action::make('send_to_cashier')
                    ->label('Send to Cashier')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->visible(fn (Laboratory $record): bool => blank($record->service_request_id) && filled($record->patient_id))
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
                    ])
                    ->action(function (Laboratory $record, array $data): void {
                        $service = Service::find($data['service_id']);

                        if (! $service) {
                            Notification::make()
                                ->title('Selected lab service was not found.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $serviceRequest = ServiceRequest::create([
                            'request_number' => 'SR-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                            'patient_id' => $record->patient_id,
                            'encounter_type' => $record->encounter_type,
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
                            'notes' => 'Laboratory request. Patient must clear payment before sample processing.',
                        ]);

                        $record->update([
                            'service_request_id' => $serviceRequest->id,
                            'cost' => $service->price,
                            'status' => 'ordered',
                        ]);

                        Notification::make()
                            ->title('Patient sent to cashier.')
                            ->body("Request: {$serviceRequest->request_number}")
                            ->success()
                            ->send();
                    }),
                Action::make('record_results')
                    ->label('Record Results')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->visible(fn (Laboratory $record): bool => $record->paymentCleared() && $record->status !== 'completed')
                      ->form([
                        Select::make('result_status')
                            ->label('Result Status') // Good practice to add label
                            ->options([
                                'normal' => 'Normal',
                                'abnormal' => 'Abnormal',
                                'critical' => 'Critical',
                            ])
                            ->required()
                            ->default('normal'), // Set a default value helps prevent empty submits
                        KeyValue::make('result_data')
                            ->label('Test Parameters (e.g., Hb, Glucose)')
                            ->keyLabel('Parameter')
                            ->valueLabel('Value'),
                        Textarea::make('notes')
                            ->label('Technician Remarks'),
                    ])
                    ->action(function (Laboratory $record, array $data): void {
                        if (! $record->paymentCleared()) {
                            Notification::make()
                                ->title('Payment is not cleared.')
                                ->body('Send the patient to cashier and wait until payment is completed.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // 1. Prepare the data to be saved
                        $resultData = [
                            'test_name' => $record->test_name,
                            'result_status' => $data['result_status'],
                            'parameters' => $data['result_data'],
                            'notes' => $data['notes'],
                            'technician_date' => now()->toDateTimeString(),
                        ];

                        // 2. Generate a unique filename for the report (e.g., JSON or PDF)
                        // Storing as JSON in this example for simplicity of data retrieval
                        $filename = "lab_reports/{$record->lab_id}_results.json";
                        \Storage::disk('public')->put($filename, json_encode($resultData));

                        $updates = [
                            'notes' => $data['notes'],
                            'result_value' => json_encode($data['result_data'] ?? []),
                            'result_status' => $data['result_status'],
                            'status' => 'completed',
                            'result_date' => now(),
                            'encounter_type' => $record->encounter_type ?? 'OPD', // Ensure type is set
                        ];

                        foreach (['result_data', 'report_path', 'lab_report_path'] as $column) {
                            if (Schema::hasColumn('laboratories', $column)) {
                                $updates[$column] = $column === 'result_data'
                                    ? ($data['result_data'] ?? [])
                                    : $filename;
                            }
                        }

                        // 3. Update the Laboratory record
                        $record->update($updates);
                        $record->serviceRequest?->update([
                            'fulfillment_status' => 'completed',
                        ]);

                        // 4. Logic to send/update IPD or OPD
                        if ($record->encounter_type === 'IPD') {
                            // Update the IPD record
                            // Assuming you have an Ipd model and a relationship or ID to link to
                            if ($record->encounter_id) {
                                $ipd = IPD::query()->where('encounter_id', $record->encounter_id)->first();
                                if ($ipd) {
                                    // Update IPD to show lab report is ready
                                    // This assumes your IPD table has a 'lab_report_path' or similar column
                                    $ipdUpdates = [];

                                    if (Schema::hasColumn('i_p_d_s', 'lab_report_status')) {
                                        $ipdUpdates['lab_report_status'] = 'completed';
                                    }

                                    if (Schema::hasColumn('i_p_d_s', 'lab_report_path')) {
                                        $ipdUpdates['lab_report_path'] = $filename;
                                    }

                                    if ($ipdUpdates !== []) {
                                        $ipd->update($ipdUpdates);
                                    }

                                    // Notify IPD Staff
                          Notification::make()
                                        ->title('Lab Result Added to IPD')
                                        ->body("Result for {$record->patient->full_name} is available in IPD #{$ipd->ipd_id}.")
                                        ->success()
                                        ->sendToDatabase(User::role('ipd_staff')->get());
                                }
                            }
                        } else {
                            // Update the OPD record
                            // Assuming you have an Opd model
                            if ($record->encounter_id) {
                                $opd = OPD::query()->where('encounter_id', $record->encounter_id)->first();
                                if ($opd) {
                                    // Update OPD to show lab report is ready
                                    $opdUpdates = [];

                                    if (Schema::hasColumn('o_p_d_s', 'lab_report_status')) {
                                        $opdUpdates['lab_report_status'] = 'completed';
                                    }

                                    if (Schema::hasColumn('o_p_d_s', 'lab_report_path')) {
                                        $opdUpdates['lab_report_path'] = $filename;
                                    }

                                    if ($opdUpdates !== []) {
                                        $opd->update($opdUpdates);
                                    }

                                    // Notify Doctor
                                  Notification::make()
                                        ->title('Lab Result Added to OPD')
                                        ->body("Result for {$record->patient->full_name} is available in OPD #{$opd->opd_id}.")
                                        ->success()
                                        ->sendToDatabase($record->doctor->user ?? User::role('doctor')->get());
                                }
                            }
                        }
                    }),
                Action::make('send_to_doctor')
                    ->label('Send to Doctor')
                    ->icon('heroicon-o-user-plus')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Laboratory $record): bool => $record->status === 'completed')
                    ->action(function (Laboratory $record): void {
                        $existingPrescription = Prescription::query()
                            ->where('patient_id', $record->patient_id)
                            ->where('doctor_id', $record->doctor_id)
                            ->whereDate('created_at', now()->toDateString())
                            ->exists();

                        if ($existingPrescription) {
                            Notification::make()
                                ->title('Prescription already created for today.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $opdEncounterId = OPD::query()
                            ->where('patient_id', $record->patient_id)
                            ->orderByDesc('id')
                            ->value('encounter_id');

                        $prescriptionId = 'PRX-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

                        Prescription::create([
                            'prescription_id' => $prescriptionId,
                            'patient_id' => $record->patient_id,
                            'doctor_id' => $record->doctor_id,
                            'encounter_type' => 'OPD',
                            'encounter_id' => $opdEncounterId,
                            'prescribed_date' => now()->toDateString(),
                            'diagnosis' => $record->notes ?: 'Review laboratory result.',
                            'status' => 'active',
                        ]);

                        $doctorUsers = User::role('doctor')->get();
                        if ($doctorUsers->isNotEmpty()) {
                            Notification::make()
                                ->title('New Doctor Queue')
                                ->body("Lab completed for {$record->patient?->full_name}.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($doctorUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to Doctor queue.')
                            ->body("Prescription ID: {$prescriptionId}")
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
