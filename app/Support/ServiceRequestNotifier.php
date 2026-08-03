<?php

namespace App\Support;

use App\Filament\Resources\ServiceRequests\ServiceRequestResource;
use App\Models\ServiceRequest;
use App\Models\User;
use Filament\NotificationAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class ServiceRequestNotifier
{
    /**
     * @return Collection<int, User>
     */
    public static function cashierUsers(): Collection
    {
        return self::usersWithRoles([
            'admin',
            'super_admin',
            'cashier',
            'casher',
            'billing_staff',
            'accountant',
        ]);
    }

    /**
     * @return Collection<int, User>
     */
    public static function departmentUsers(ServiceRequest $serviceRequest): Collection
    {
        $roles = match ($serviceRequest->service?->service_type) {
            'lab' => ['lab_staff', 'laboratory_staff', 'lab_technician'],
            'radiology' => ['radiology_staff', 'radiology_technician'],
            'pharmacy' => ['pharmacy_staff', 'pharmacist'],
            'bed_charges', 'surgery' => ['ipd_staff', 'nurse'],
            'consultation', 'emergency' => ['opd_staff', 'doctor', 'nurse'],
            default => ['admin', 'super_admin'],
        };

        return self::usersWithRoles($roles);
    }

    public static function notifyCashiers(ServiceRequest $serviceRequest): void
    {
        $cashiers = self::cashierUsers();

        if ($cashiers->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('New payment request')
            ->body(self::paymentRequestBody($serviceRequest))
            ->icon('heroicon-o-banknotes')
            ->warning()
          //  ->actions([
              //  NotificationAction::make('open_request')
                 //   ->label('Open cashier queue')
               //     ->url(ServiceRequestResource::getUrl('edit', ['record' => $serviceRequest])),
           // ])
            ->sendToDatabase($cashiers);
    }

    public static function notifyDepartmentPaymentCleared(ServiceRequest $serviceRequest): void
    {
        $receivers = self::departmentUsers($serviceRequest);

        if ($receivers->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('Payment cleared')
            ->body(self::paymentClearedBody($serviceRequest))
            ->icon('heroicon-o-check-circle')
            ->success()
           // ->actions([
             //   NotificationAction::make('open_request')
              //      ->label('Open request')
                 //   ->url(ServiceRequestResource::getUrl('edit', ['record' => $serviceRequest])),
         //   ])
            ->sendToDatabase($receivers);
    }

    /**
     * @return Collection<int, User>
     */
    private static function usersWithRoles(array $roles): Collection
    {
        return User::query()
            ->where(fn ($query) => $query
                ->where('status', 'active')
                ->orWhereNull('status'))
            ->whereHas('roles', fn ($query) => $query->whereIn('name', $roles))
            ->get();
    }

    private static function paymentRequestBody(ServiceRequest $serviceRequest): string
    {
        $serviceRequest->loadMissing(['patient', 'service', 'requester']);

        return sprintf(
            '%s needs %s payment for %s. Patient share: ETB %s. Sent by %s.',
            $serviceRequest->patient?->full_name ?? 'Patient',
            $serviceRequest->service?->service_type ?? 'service',
            $serviceRequest->service?->name ?? $serviceRequest->request_number,
            number_format((float) $serviceRequest->patient_share, 2),
            $serviceRequest->requester?->name ?? 'system',
        );
    }

    private static function paymentClearedBody(ServiceRequest $serviceRequest): string
    {
        $serviceRequest->loadMissing(['patient', 'service', 'collector']);

        return sprintf(
            '%s can proceed with %s. Request %s was cleared by %s.',
            $serviceRequest->patient?->full_name ?? 'Patient',
            $serviceRequest->service?->name ?? 'service',
            $serviceRequest->request_number,
            $serviceRequest->collector?->name ?? $serviceRequest->verifier?->name ?? 'cashier',
        );
    }
}
