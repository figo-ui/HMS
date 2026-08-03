<x-filament-panels::page>
    @php
        $cards = [
            [
                'label' => 'Purchase Quantity',
                'value' => number_format($summary['purchase_qty']),
                'meta' => 'Purchase value: ' . number_format($summary['purchase_value'], 2) . ' ETB',
                'tone' => 'from-emerald-500 to-teal-600',
            ],
            [
                'label' => 'Direct Sales',
                'value' => number_format($summary['direct_sale_qty']),
                'meta' => 'OTC and walk-in sales',
                'tone' => 'from-sky-500 to-blue-600',
            ],
            [
                'label' => 'Prescription Sales',
                'value' => number_format($summary['prescription_sale_qty']),
                'meta' => 'Pharmacist completed sales',
                'tone' => 'from-violet-500 to-indigo-600',
            ],
            [
                'label' => 'Dispensed Quantity',
                'value' => number_format($summary['dispense_qty']),
                'meta' => 'Inventory marked as dispensed',
                'tone' => 'from-amber-400 to-orange-500',
            ],
        ];
    @endphp

    <style>
        .pharmacy-report-shell {
            --report-ink: #0f172a;
            --report-muted: #64748b;
            --report-line: rgba(148, 163, 184, 0.22);
            --report-panel: rgba(255, 255, 255, 0.92);
            --report-wash: linear-gradient(180deg, rgba(255,255,255,0.72), rgba(255,255,255,0.92));
        }

        .pharmacy-report-shell .report-glass {
            background: var(--report-wash);
            border: 1px solid var(--report-line);
            backdrop-filter: blur(12px);
        }

        .pharmacy-report-shell .report-table thead th {
            font-size: 0.72rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .pharmacy-report-shell .report-table tbody tr:nth-child(even) {
            background: rgba(248, 250, 252, 0.85);
        }

        .pharmacy-report-shell .report-table tbody tr:hover {
            background: rgba(224, 242, 254, 0.55);
        }

        .pharmacy-report-shell .report-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 9999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.78rem;
            font-weight: 600;
        }
    </style>

    <div class="pharmacy-report-shell space-y-8">
        <section class="relative overflow-hidden rounded-[2rem] bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.35),_transparent_34%),linear-gradient(135deg,_#082f49_0%,_#0f766e_45%,_#111827_100%)] p-6 text-white shadow-[0_25px_80px_rgba(8,47,73,0.35)] md:p-8">
            <div class="absolute inset-y-0 right-0 hidden w-1/3 bg-[radial-gradient(circle_at_center,_rgba(250,204,21,0.16),_transparent_58%)] lg:block"></div>

            <div class="relative grid gap-6 xl:grid-cols-[1.2fr_0.9fr]">
                <div class="space-y-4">
                    <div class="report-pill bg-white/10 text-cyan-50 ring-1 ring-white/20">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                        Pharmacy Performance Window
                    </div>

                    <div class="max-w-3xl">
                        <h2 class="text-3xl font-semibold tracking-tight md:text-4xl">Pharmacy Report Dashboard</h2>
                        <p class="mt-3 text-sm leading-6 text-cyan-50/85 md:text-base">
                            Review purchase activity, direct sales, prescription dispensing, and pharmacy revenue with one clean operational view.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-cyan-100/80">Date Range</p>
                            <p class="mt-2 text-lg font-semibold">{{ $startDate }} to {{ $endDate }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-cyan-100/80">Sales Revenue</p>
                            <p class="mt-2 text-lg font-semibold">{{ number_format($summary['sales_value'], 2) }} ETB</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-cyan-100/80">Dispense Volume</p>
                            <p class="mt-2 text-lg font-semibold">{{ number_format($summary['dispense_qty']) }}</p>
                        </div>
                    </div>
                </div>

                <form method="GET" class="report-glass rounded-[1.75rem] p-5 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)]">
                    <div class="grid gap-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Filter Report Range</p>
                            <p class="mt-1 text-sm text-slate-500">Update the analytics window or export exactly what you are seeing.</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="text-sm">
                                <span class="mb-2 block font-medium text-slate-700">Start Date</span>
                                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                            </label>
                            <label class="text-sm">
                                <span class="mb-2 block font-medium text-slate-700">End Date</span>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                            </label>
                        </div>

                        <div class="grid gap-9 sm:grid-cols-2md:grid-cols-3">
                            <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                                Apply Filters
                            </button>
                            <a href="{{ $csvUrl }}" class="rounded-2xl bg-cyan-500 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-cyan-400">
                                Export CSV
                            </a>
                            <a href="{{ $printUrl }}" target="_blank" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Print / PDF
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="grid gap-7 md:grid-cols-2 2xl:grid-cols-4">
            @foreach ($cards as $card)
                <article class="relative overflow-hidden rounded-[1.6rem] bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $card['tone'] }}"></div>
                    <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-4 text-4xl font-semibold tracking-tight text-slate-900">{{ $card['value'] }}</p>
                    <p class="mt-3 text-sm text-slate-500">{{ $card['meta'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.25fr_0.95fr]">
            <div class="report-glass rounded-[1.75rem] p-5 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Recent Stock Movements</h3>
                        <p class="mt-1 text-sm text-slate-500">Purchase, sale, dispense, expiry, and adjustment history.</p>
                    </div>
                    <div class="report-pill bg-sky-50 text-sky-700 ring-1 ring-sky-100">
                        {{ $startDate }} to {{ $endDate }}
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="report-table w-full border-1 overflow-hidden rounded-2xl text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-slate-500">
                                <th class="py-3 pr-4">Date</th>
                                <th class="py-3 pr-4">Medicine</th>
                                <th class="py-3 pr-4">Type</th>
                                <th class="py-3 pr-4">Qty</th>
                                <th class="py-3">Reference</th>
                            </tr>
                        </thead>
                        <tbody border-1>
                            @forelse ($recentTransactions as $transaction)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-4 text-slate-600">{{ optional($transaction->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="py-3 pr-4 font-medium text-slate-900">{{ $transaction->inventory?->item_name ?? $transaction->inventory_id }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="report-pill bg-slate-100 text-slate-700 ring-1 ring-slate-200">
                                            {{ ucfirst($transaction->operation_type) }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 font-semibold text-slate-900">{{ $transaction->quantity }}</td>
                                    <td class="py-3 text-slate-600">{{ $transaction->reference_id ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500">No stock movements found in this range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="report-glass rounded-[1.75rem] p-5 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Recent Pharmacy Sales</h3>
                        <p class="mt-1 text-sm text-slate-500">The latest direct and prescription sales recorded by the team.</p>
                    </div>
                    <div class="report-pill bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                        Latest 12 records
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="report-table w-full border-1 text-sm"> 
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-slate-500">
                                <th class="py-3 pr-4">Sale ID</th>
                                <th class="py-3 pr-4">Patient</th>
                                <th class="py-3 pr-4">Medicine</th>
                                <th class="py-3 pr-4">Qty</th>
                                <th class="py-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody border-2>
                            @forelse ($recentSales as $sale)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-4 font-medium text-slate-900">{{ $sale->sale_id }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $sale->patient?->full_name ?? '-' }}</td>
                                    <td class="py-3 pr-4 text-slate-900">{{ $sale->medicine_name }}</td>
                                    <td class="py-3 pr-4 font-semibold text-slate-900">{{ $sale->quantity }}</td>
                                    <td class="py-3 font-semibold text-emerald-700">{{ number_format($sale->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500">No pharmacy sales found in this range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-10 xl:grid-cols-[0.95fr_1.25fr]">
            <div class="rounded-[1.75rem] bg-gradient-to-br from-slate-900 via-slate-800 to-cyan-900 p-5 text-white shadow-[0_18px_55px_rgba(15,23,42,0.25)]">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Revenue Snapshot</h3>
                        <p class="mt-1 text-sm text-slate-200/80">Quick financial readout for the selected reporting window.</p>
                    </div>
                    <div class="report-pill bg-white/10 text-white ring-1 ring-white/15">
                        ETB
                    </div>
                </div>

                <div class="mt-8 space-y-5">
                    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                        <p class="text-sm text-slate-200/80">Sales Revenue</p>
                        <p class="mt-2 text-3xl font-semibold">{{ number_format($summary['sales_value'], 2) }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                            <p class="text-sm text-slate-200/80">Purchase Value</p>
                            <p class="mt-2 text-xl font-semibold">{{ number_format($summary['purchase_value'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                            <p class="text-sm text-slate-200/80">Dispense Qty</p>
                            <p class="mt-2 text-xl font-semibold">{{ number_format($summary['dispense_qty']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-glass rounded-[1.75rem] p-5 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Top Medicines</h3>
                        <p class="mt-1 text-sm text-slate-500">Best-selling items ranked by total quantity sold.</p>
                    </div>
                    <div class="report-pill bg-amber-50 text-amber-700 ring-1 ring-amber-100">
                        Ranked by quantity
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="report-table w-full border-1 text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-slate-500">
                                <th class="py-3 pr-4">Medicine</th>
                                <th class="py-3 pr-4">Total Qty</th>
                                <th class="py-3">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody border-2>
                            @forelse ($topMedicines as $medicine)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-4 font-medium text-slate-900">{{ $medicine->medicine_name }}</td>
                                    <td class="py-3 pr-4 font-semibold text-slate-900">{{ number_format($medicine->total_quantity) }}</td>
                                    <td class="py-3 font-semibold text-cyan-700">{{ number_format($medicine->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-slate-500">No sales data found in this range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
