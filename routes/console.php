<?php

use App\Mail\TestEmailMail;
use App\Models\Patients;
use App\Support\PatientPortalProvisioner;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('receptionist:setup {--user_id=} {--user_email=}', function () {
    $permissions = [
        'View:Dashboard',
        'View:HospitalStatsOverview',
        'View:HospitalIncomeExpenseChart',
        'View:HospitalAppointmentsPieChart',
        'View:HospitalReportsTypeChart',
        'ViewAny:Patients',
        'View:Patients',
        'Create:Patients',
        'Update:Patients',
        'ViewAny:Appointments',
        'View:Appointments',
        'Create:Appointments',
        'Update:Appointments',
        'ViewAny:Triage',
        'View:Triage',
        'Create:Triage',
        'Update:Triage',
        'ViewAny:Radiology',
        'View:Radiology',
        'ViewAny:IPD',
        'View:IPD',
        'Create:IPD',
        'Update:IPD',
        'ViewAny:BillingPayment',
        'View:BillingPayment',
        'Create:BillingPayment',
        'Update:BillingPayment',
        'ViewAny:Prescription',
        'View:Prescription',
        'Create:Prescription',
        'Update:Prescription',
    ];

    $role = Role::firstOrCreate([
        'name' => 'receptionist',
        'guard_name' => 'web',
    ]);

    $role->syncPermissions($permissions);
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->info("Role '{$role->name}' configured with {$role->permissions()->count()} permissions.");

    $userId = $this->option('user_id');
    $userEmail = $this->option('user_email');

    if (! $userId && ! $userEmail) {
        $this->line('No user assignment requested. Use --user_id=ID or --user_email=EMAIL to assign role.');

        return;
    }

    $user = $userId
        ? User::query()->find($userId)
        : User::query()->where('email', $userEmail)->first();

    if (! $user) {
        $this->error('User not found for assignment.');

        return;
    }

    $user->assignRole('receptionist');
    $this->info("Assigned role 'receptionist' to user: {$user->email}");
})->purpose('Create/update receptionist role and optionally assign it to a user.');

Artisan::command('workflow:setup-roles', function () {
    $rolesPermissions = [
        'triage_nurse' => [
            'View:Dashboard',
            'ViewAny:Triage',
            'View:Triage',
            'Create:Triage',
            'Update:Triage',
            'Create:Laboratory',
            'Create:Radiology',
            'ViewAny:Patients',
            'View:Patients',
            'ViewAny:OPD',
            'Create:OPD',
        ],
        'opd_staff' => [
            'View:Dashboard',
            'ViewAny:OPD',
            'View:OPD',
            'Update:OPD',
            'ViewAny:Triage',
            'View:Triage',
            'ViewAny:Patients',
            'View:Patients',
            'Create:Laboratory',
            'Create:Radiology',
            'ViewAny:Laboratory',
            'View:Laboratory',
            'ViewAny:Radiology',
            'View:Radiology',
        ],
        'lab_technician' => [
            'View:Dashboard',
            'ViewAny:Laboratory',
            'View:Laboratory',
            'Update:Laboratory',
            'ViewAny:Patients',
            'View:Patients',
            'Create:Prescription',
            'ViewAny:Prescription',
            'View:Prescription',
        ],
        'radiology_technician' => [
            'View:Dashboard',
            'ViewAny:Radiology',
            'View:Radiology',
            'Update:Radiology',
            'ViewAny:Patients',
            'View:Patients',
        ],
        'doctor' => [
            'View:Dashboard',
            'ViewAny:OPD',
            'View:OPD',
            'Update:OPD',
            'ViewAny:Laboratory',
            'View:Laboratory',
            'ViewAny:Radiology',
            'View:Radiology',
            'ViewAny:Prescription',
            'View:Prescription',
            'Create:Prescription',
            'Update:Prescription',
            'ViewAny:Patients',
            'View:Patients',
        ],
        'pharmacist' => [
            'View:Dashboard',
            'ViewAny:Pharmacy',
            'View:Pharmacy',
            'Create:Pharmacy',
            'Update:Pharmacy',
            'Delete:Pharmacy',
            'ViewAny:Prescription',
            'View:Prescription',
            'ViewAny:Patients',
            'View:Patients',
        ],
    ];

    foreach ($rolesPermissions as $roleName => $permissions) {
        $role = Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($permissions);
        $this->info("Role '{$roleName}' configured with {$role->permissions()->count()} permissions.");
    }

    app(PermissionRegistrar::class)->forgetCachedPermissions();
    $this->info('Workflow roles setup completed.');
})->purpose('Create/update triage_nurse, opd_staff, lab_technician, radiology_technician, doctor, and pharmacist roles for workflow.');

Artisan::command('workflow:assign-role {role} {--user_id=} {--user_email=}', function (string $role) {
    $userId = $this->option('user_id');
    $userEmail = $this->option('user_email');

    if (! $userId && ! $userEmail) {
        $this->error('Provide --user_id=ID or --user_email=EMAIL.');

        return;
    }

    $targetRole = Role::query()
        ->where('name', $role)
        ->where('guard_name', 'web')
        ->first();

    if (! $targetRole) {
        $this->error("Role '{$role}' not found. Run: php artisan workflow:setup-roles");

        return;
    }

    $user = $userId
        ? User::query()->find($userId)
        : User::query()->where('email', $userEmail)->first();

    if (! $user) {
        $this->error('User not found.');

        return;
    }

    $user->syncRoles([$targetRole->name]);
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->info("Assigned role '{$targetRole->name}' to {$user->email}.");
})->purpose('Assign one workflow role to a specific user.');

Artisan::command('mail:test {email}', function (string $email) {
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Please provide a valid email address.');

        return;
    }

    try {
        Mail::to($email)->send(new TestEmailMail($email));
        $this->info("Test email sent successfully to {$email}.");
    } catch (Throwable $exception) {
        report($exception);
        $this->error('Test email could not be sent. Check your MAIL_* settings and logs.');
    }
})->purpose('Send a test email to verify mail configuration.');

Artisan::command('patients:backfill-portal {--send-email} {--patient_id=} {--force}', function () {
    $query = Patients::query()->orderBy('id');

    if ($patientId = $this->option('patient_id')) {
        $query->where('patient_id', $patientId);
    }

    $patients = $query->get();

    if ($patients->isEmpty()) {
        $this->warn('No patients found for backfill.');

        return;
    }

    $sendEmail = (bool) $this->option('send-email');
    $force = (bool) $this->option('force');

    $created = 0;
    $updated = 0;
    $skipped = 0;

    foreach ($patients as $patient) {
        $hasPortal = filled($patient->user_id) && $patient->author?->hasRole('patient');

        if ($hasPortal && ! $force) {
            $this->line("Skipped {$patient->full_name} ({$patient->patient_id}) - portal already exists.");
            $skipped++;

            continue;
        }

        try {
            $hadUser = filled($patient->user_id);
            $result = PatientPortalProvisioner::provision($patient, $sendEmail);

            $status = $hadUser ? 'updated' : 'created';
            $emailInfo = $result['email_sent'] ? 'email sent' : 'email not sent';

            $this->info(strtoupper($status) . ": {$patient->full_name} ({$patient->patient_id}) -> {$result['email']} | {$emailInfo}");

            if ($hadUser) {
                $updated++;
            } else {
                $created++;
            }
        } catch (Throwable $exception) {
            report($exception);
            $this->error("Failed: {$patient->full_name} ({$patient->patient_id})");
        }
    }

    $this->newLine();
    $this->table(
        ['Created', 'Updated', 'Skipped'],
        [[$created, $updated, $skipped]]
    );
})->purpose('Create patient portal accounts for existing patients and optionally email credentials.');
