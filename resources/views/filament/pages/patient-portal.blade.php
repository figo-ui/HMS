<x-filament-panels::page>
    @php
        $profileItems = [
            'Patient ID' => $patient?->patient_id ?? '-',
            'MRN' => $patient?->mrn ?? '-',
            'Name' => $patient?->full_name ?? '-',
            'Email' => $patient?->email ?? '-',
            'Phone' => $patient?->phone ?? '-',
            'Blood Group' => $patient?->blood_group ?? '-',
            'Status' => $patient?->status ?? '-',
        ];
    @endphp

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 xl:col-span-1">
            <h2 class="text-lg font-semibold text-slate-900">My Profile</h2>
            <p class="mt-1 text-sm text-slate-500">Private patient information linked to your account.</p>

            <dl class="mt-5 space-y-3">
                @foreach ($profileItems as $label => $value)
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-3">
                        <dt class="text-sm font-medium text-slate-500">{{ $label }}</dt>
                        <dd class="text-right text-sm text-slate-900">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="mt-5 rounded-xl bg-amber-50 p-4 text-sm text-amber-900">
                Outstanding Balance: <strong>{{ number_format($outstandingBalance, 2) }}</strong>
            </div>
        </section>

        <section class="space-y-6 xl:col-span-2">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Appointments</h2>
                    <div class="mt-4">
                        <table class="w-full border-collapse border border-gray-200 text-xs">
                            <thead>
                                <tr class="bg-teal-50">
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Doctor</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Status</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $appointment)
                                    <tr class="even:bg-gray-50">
                                        <td class="border border-gray-200 p-2">{{ $appointment->doctor?->full_name ?? 'Doctor not assigned' }}</td>
                                        <td class="border border-gray-200 p-2"><span class="rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold uppercase text-sky-700">{{ $appointment->status }}</span></td>
                                        <td class="border border-gray-200 p-2">{{ optional($appointment->date)->format('M d, Y') }} at {{ $appointment->time }}</td>
                                    </tr>
                                @empty
                                    <tr class="even:bg-gray-50"><td colspan="3" class="border border-gray-200 p-2 text-center text-slate-500">No appointments found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Prescriptions</h2>
                    <div class="mt-4">
                        <table class="w-full border-collapse border border-gray-200 text-xs">
                            <thead>
                                <tr class="bg-teal-50">
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Doctor</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Status</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Date & ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prescriptions as $prescription)
                                    <tr class="even:bg-gray-50">
                                        <td class="border border-gray-200 p-2">{{ $prescription->doctor?->full_name ?? 'Doctor' }}</td>
                                        <td class="border border-gray-200 p-2"><span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold uppercase text-emerald-700">{{ $prescription->status }}</span></td>
                                        <td class="border border-gray-200 p-2">{{ optional($prescription->prescribed_date)->format('M d, Y') }} | {{ $prescription->prescription_id }}</td>
                                    </tr>
                                @empty
                                    <tr class="even:bg-gray-50"><td colspan="3" class="border border-gray-200 p-2 text-center text-slate-500">No prescriptions found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">History</h2>
                    <div class="mt-4">
                        <table class="w-full border-collapse border border-gray-200 text-xs">
                            <thead>
                                <tr class="bg-teal-50">
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Activity</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($histories as $history)
                                    <tr class="even:bg-gray-50">
                                        <td class="border border-gray-200 p-2">{{ $history->activity }}</td>
                                        <td class="border border-gray-200 p-2">{{ $history->details ?: 'No details recorded.' }}</td>
                                    </tr>
                                @empty
                                    <tr class="even:bg-gray-50"><td colspan="2" class="border border-gray-200 p-2 text-center text-slate-500">No history records found.</td></tr>
                                @endforelse
                            </tbody>
                        <table class="w-full border-collapse border border-gray-200 text-xs">
                            <thead>
                                <tr class="bg-teal-50">
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Title</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Body</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    <tr class="even:bg-gray-50">
                                        <td class="border border-gray-200 p-2">{{ data_get($notification->data, 'title', 'Notification') }}</td>
                                        <td class="border border-gray-200 p-2">{{ data_get($notification->data, 'body', '-') }}</td>
                                    </tr>
                                @empty
                                    <tr class="even:bg-gray-50"><td colspan="2" class="border border-gray-200 p-2 text-center text-slate-500">No notifications found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Notifications</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($notifications as $notification)
                            <div class="rounded-xl border border-slate-200 p-4">
                                <div class="font-medium text-slate-900">{{ data_get($notification->data, 'title', 'Notification') }}</div>
                                <div class="mt-1 text-sm text-slate-600">{{ data_get($notification->data, 'body', '-') }}</div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No notifications found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Lab Reports</h2>
                    <div class="mt-4">
                        <table class="w-full border-collapse border border-gray-200 text-xs">
                            <thead>
                                <tr class="bg-teal-50">
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Test Name</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Date & Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($labReports as $report)
                                    <tr class="even:bg-gray-50">
                                        <td class="border border-gray-200 p-2">{{ $report->test_name }}</td>
                                        <td class="border border-gray-200 p-2">{{ optional($report->test_date)->format('M d, Y') }} | {{ $report->result_status }}</td>
                                    </tr>
                                @empty
                                    <tr class="even:bg-gray-50"><td colspan="2" class="border border-gray-200 p-2 text-center text-slate-500">No lab reports found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Radiology Reports</h2>
                    <div class="mt-4">
                        <table class="w-full border-collapse border border-gray-200 text-xs">
                            <thead>
                                <tr class="bg-blue-50">
                                    <th class="border border-slate-200 p-2 text-left font-medium text-blue-900">Exam Name</th>
                                    <th class="border border-slate-200 p-2 text-left font-medium text-blue-900">Date & Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($radiologyReports as $report)
                                    <tr class="even:bg-gray-50">
                                        <td class="border border-gray-200 p-2">{{ $report->exam_name }}</td>
                                        <td class="border border-gray-200 p-2">{{ optional($report->exam_date)->format('M d, Y') }} | {{ $report->result_status }}</td>
                                    </tr>
                                @empty
                                    <tr class="even:bg-gray-50"><td colspan="2" class="border border-gray-200 p-2 text-center text-slate-500">No radiology reports found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Pharmacy</h2>
                    <div class="mt-4">
                        <table class="w-full border-collapse border border-gray-200 text-xs">
                            <thead>
                                <tr class="bg-teal-50">
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Medicine Name</th>
                                    <th class="border border-gray-200 p-2 text-left font-medium text-teal-800 text-sm">Prescription Sale ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pharmacyItems as $item)
                                    <tr class="even:bg-gray-50">
                                        <td class="border border-gray-200 p-2">{{ $item->medicine_name }}</td>
                                        <td class="border border-gray-200 p-2">{{ $item->prescription_sale_id ?: 'No prescription reference' }}</td>
                                    </tr>
                                @empty
                                    <tr class="even:bg-gray-50"><td colspan="2" class="border border-gray-200 p-2 text-center text-slate-500">No pharmacy records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
