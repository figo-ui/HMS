<?php

namespace App\Filament\Pages;

use App\Models\Appointments;
use App\Models\BillingPayment;
use App\Models\Laboratory;
use App\Models\PatientHistory;
use App\Models\Pharmacy;
use App\Models\Prescription;
use App\Models\Radiology;
use App\Support\PatientPortal as PatientPortalSupport;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class PatientPortal extends Page
{
    use HasPageShield;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected string $view = 'filament.pages.patient-portal';

    protected static ?string $title = 'My Portal';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return PatientPortalSupport::isPatientUser();
    }

    public function getViewData(): array
    {
        $patient = PatientPortalSupport::currentPatient();
        $patientId = $patient?->id;
        $user = auth()->user();
        return [
            'patient' => $patient,
            'appointments' => $patientId
                ? Appointments::query()->with('doctor')->where('patient_id', $patientId)->latest('date')->limit(5)->get()
                : collect(),
            'prescriptions' => $patientId
                ? Prescription::query()->with('doctor')->where('patient_id', $patientId)->latest('prescribed_date')->limit(5)->get()
                : collect(),
            'histories' => $patientId
                ? PatientHistory::query()->where('patient_id', $patientId)->latest('occurred_at')->limit(6)->get()
                : collect(),

            'labReports' => $patientId
                ? Laboratory::query()->with('doctor')->where('patient_id', $patientId)->latest('test_date')->limit(5)->get()
                : collect(),
            'radiologyReports' => $patientId
                ? Radiology::query()->with('doctor')->where('patient_id', $patientId)->latest('exam_date')->limit(5)->get()
                : collect(),
            'pharmacyItems' => $patientId
                ? Pharmacy::query()->where('issued_to_patient_id', $patientId)->latest()->limit(5)->get()
                : collect(),
            'notifications' => $user
                ? $user->notifications()->latest()->limit(6)->get()
                : collect(),
            'outstandingBalance' => $patientId
                ? (float) BillingPayment::query()->where('patient_id', $patientId)->sum('balance')
                : 0.0,
                
        ];
        
    }
}
