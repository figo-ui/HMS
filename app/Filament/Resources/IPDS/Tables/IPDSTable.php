<?php

namespace App\Filament\Resources\IPDS\Tables;

use App\Filament\Support\TableFilters;
use App\Models\IPD;
use App\Models\Laboratory;
use App\Models\PatientHistory;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
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
class IPDSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('encounter_id')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('doctor.full_name')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('admission_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('discharge_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('bed.id')
                    ->searchable(),
                TextColumn::make('prescription_id')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
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
                TableFilters::distinct('department', IPD::class, 'Department'),
                TableFilters::distinct('bed_id', IPD::class, 'Bed'),
                TableFilters::select('type', [

                    'IPD' => 'IPD',
                ]),
                TableFilters::select('status', [
                    'admitted' => 'Admitted',
                    'discharged' => 'Discharged',
                    'transferred' => 'Transferred',
                ]),
                TableFilters::dateRange('admission_date', 'Admission Date'),
                TableFilters::dateRange('discharge_date', 'Discharge Date'),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)

           ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                 DeleteAction::make(),
                Action::make('call_patient')
                    ->label('Call')
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->url(fn (IPD $record): ?string => filled($record->patient?->phone) ? 'tel:' . $record->patient->phone : null)
                    ->visible(fn (IPD $record): bool => filled($record->patient?->phone)),
                Action::make('send_to_lab')
                    ->label('Send to Lab')
                    ->icon('heroicon-o-beaker')
                    ->color('warning')
                    ->visible(fn (IPD $record): bool => filled($record->patient_id) && filled($record->doctor_id))
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
                            ->default('Initial IPD Lab Panel')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (IPD $record, array $data): void {
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
                            'encounter_type' => 'IPD',
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
                            'notes' => 'IPD laboratory request. Patient must clear payment before sample processing.',
                        ]);

                        Laboratory::create([
                            'lab_id' => $labId,
                            'patient_id' => $record->patient_id,
                            'doctor_id' => $record->doctor_id,
                            'service_request_id' => $serviceRequest->id,
                            'encounter_type' => 'IPD',
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
                                ->body("IPD patient {$record->patient?->full_name} sent to Lab.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($labUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to cashier for Lab payment.')
                            ->body("Lab ID: {$labId}. Request: {$serviceRequest->request_number}")
                            ->success()
                            ->send();
                    }),
                 Action::make('send_to_radiology')
                    ->label('Send to Radiology')
                    ->icon('heroicon-o-photo')
                    ->color('gray')
                    ->visible(fn (IPD $record): bool => $record->status === 'admitted')
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
                            ->default('IPD Requested Imaging')
                            ->required()
                            ->rows(2),
                    ])

                    ->action(function (IPD $record, array $data): void {
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
                            'encounter_type' => 'IPD',
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
                            'notes' => 'IPD radiology request. Patient must clear payment before imaging.',
                        ]);

                        Radiology::create([
                            'radiology_id' => $radiologyId,
                            'patient_id' => $record->patient_id,
                            'doctor_id' => $data['doctor_id'],
                            'service_request_id' => $serviceRequest->id,
                            'encounter_type' => 'IPD',
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
                                ->body("IPD patient {$record->patient?->full_name} sent to Radiology.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($radiologyUsers);
                        }

                        Notification::make()
                            ->title('Patient sent to cashier for Radiology payment.')
                            ->body("Radiology ID: {$radiologyId}. Request: {$serviceRequest->request_number}")
                            ->success()
                            ->send();
             }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
