<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #0f172a; }
        h1, h2 { margin: 0 0 12px; }
        .meta { margin-bottom: 18px; color: #475569; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
        .card { border: 1px solid #cbd5e1; border-radius: 12px; padding: 14px; }
        .card small { color: #64748b; display: block; margin-bottom: 8px; }
        .card strong { font-size: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 24px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; font-size: 12px; }
        th { background: #e2e8f0; }
    </style>
</head>
<body onload="window.print()">
    <h1>Pharmacy Report</h1>
    <div class="meta">Date Range: {{ $startDate }} to {{ $endDate }}</div>

    <div class="grid">
        <div class="card"><small>Purchase Quantity</small><strong>{{ number_format($summary['purchase_qty']) }}</strong></div>
        <div class="card"><small>Direct Sales</small><strong>{{ number_format($summary['direct_sale_qty']) }}</strong></div>
        <div class="card"><small>Prescription Sales</small><strong>{{ number_format($summary['prescription_sale_qty']) }}</strong></div>
        <div class="card"><small>Dispensed Quantity</small><strong>{{ number_format($summary['dispense_qty']) }}</strong></div>
        <div class="card"><small>Purchase Value</small><strong>{{ number_format($summary['purchase_value'], 2) }}</strong></div>
        <div class="card"><small>Sales Revenue</small><strong>{{ number_format($summary['sales_value'], 2) }}</strong></div>
    </div>

    <h2>Recent Pharmacy Sales</h2>
    <table  border='1'>
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Type</th>
                <th>Patient</th>
                <th>Medicine</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Sold At</th>
            </tr>
        </thead>
        <tbody border='1'>
            @forelse ($recentSales as $sale)
                <tr>
                    <td>{{ $sale->sale_id }}</td>
                    <td>{{ $sale->sale_type }}</td>
                    <td>{{ $sale->patient?->full_name ?? '-' }}</td>
                    <td>{{ $sale->medicine_name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ optional($sale->sold_at)->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No sales found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Top Medicines</h2>
    <table border='1'>
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Total Qty</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody border='1'>
            @forelse ($topMedicines as $medicine)
                <tr>
                    <td>{{ $medicine->medicine_name }}</td>
                    <td>{{ number_format($medicine->total_quantity) }}</td>
                    <td>{{ number_format($medicine->total_amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No medicine totals found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
