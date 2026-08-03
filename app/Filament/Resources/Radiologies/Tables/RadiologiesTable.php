<?php

namespace App\Filament\Resources\Radiologies\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Radiology;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Support\ClinicalReportAccess;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\User;

class RadiologiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('radiology_id')
                    ->searchable(),
                TextColumn::make('patient.patient_id')
                    ->label('Patient ID')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable(),
                TextColumn::make('doctor.full_name')
                    ->label('Doctor')
                    ->searchable(),
                TextColumn::make('exam_name')
                    ->searchable(),
                TextColumn::make('modality')
                    ->searchable(),
                TextColumn::make('result_status')
                    ->badge()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::relationship('doctor', 'full_name', 'Doctor'),
                TableFilters::distinct('exam_name', Radiology::class, 'Exam Name'),
                TableFilters::distinct('modality', Radiology::class, 'Modality'),
                TableFilters::select('result_status', [
                    'normal' => 'Normal',
                    'abnormal' => 'Abnormal',
                    'critical' => 'Critical',
                    'pending' => 'Pending',
                ])->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TableFilters::select('status', [
                    'ordered' => 'Ordered',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
                TableFilters::dateRange('exam_date', 'Exam Date'),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([

                Action::make('send_to_cashier')
                    ->label('Send to Cashier')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->visible(fn (Radiology $record): bool => blank($record->service_request_id) && filled($record->patient_id))
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
                    ])
                    ->action(function (Radiology $record, array $data): void {
                        $service = Service::find($data['service_id']);

                        if (! $service) {
                            Notification::make()
                                ->title('Selected radiology service was not found.')
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
                            'notes' => 'Radiology request. Patient must clear payment before imaging.',
                        ]);

                        $record->update([
                            'service_request_id' => $serviceRequest->id,
                            'status' => 'ordered',
                        ]);

                        Notification::make()
                            ->title('Patient sent to cashier.')
                            ->body("Request: {$serviceRequest->request_number}")
                            ->success()
                            ->send();
                    }),
                Action::make('submit_result')
    ->label('Submit Result')
    ->icon('heroicon-o-check-circle')
    ->color('success')
    ->hidden(fn ($record) => $record->status === 'completed' || ! $record->paymentCleared())
    ->form([
        FileUpload::make('report_image')
            ->label('Upload Scan/X-Ray')
            ->directory('radiology-reports')
            ->image(),
        Textarea::make('findings')
            ->label('Radiology Findings')
            ->required()
            ->rows(5),
        Select::make('result_status')
            ->label('Result Assessment')
            ->options([
                'normal' => 'Normal',
                'abnormal' => 'Abnormal',
                'critical' => 'Critical',
            ])
            ->required(),
        Textarea::make('conclusion')
            ->label('Conclusion/Recommendation')
            ->required(),
    ])
    ->action(function ($record, array $data): void {
        if (! $record->paymentCleared()) {
            Notification::make()
                ->title('Payment is not cleared.')
                ->body('Send the patient to cashier and wait until payment is completed.')
                ->danger()
                ->send();

            return;
        }

        // 1. Update the Radiology Record
        $record->update([
            'findings' => $data['findings'],
            'conclusion' => $data['conclusion'],
            'report_image' => $data['report_image'],
            'result_status' => $data['result_status'],
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        $record->serviceRequest?->update([
            'fulfillment_status' => 'completed',
        ]);

        // 2. Find the doctor's user account to notify them
        $doctorUser = $record->doctor?->user; 

        if ($doctorUser) {
            Notification::make()
                ->title('Radiology Result Available')
                ->body("The results for patient {$record->patient?->full_name} ({$record->radiology_id}) are ready for review.")
                ->icon('heroicon-o-document-magnifying-glass')
                ->actions([
                    NotificationAction::make('view_result')
                        ->label('View Result')
                        ->url(\App\Filament\Resources\Radiologies\RadiologyResource::getUrl('view', ['record' => $record])),
                ])
                ->info()
                ->sendToDatabase($doctorUser);
        }

        Notification::make()
            ->title('Result submitted successfully')
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
