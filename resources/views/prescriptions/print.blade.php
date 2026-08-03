<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription {{ $prescription->prescription_id ?? $prescription->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
            color: #111827;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
        }
        .muted {
            color: #6b7280;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            width: 220px;
            font-weight: 600;
        }
        table tr:nth-child(odd) th,
        table tr:nth-child(odd) td {
            background: #ffffff;
        }
        table tr:nth-child(even) th,
        table tr:nth-child(even) td {
            background: #e6f0ff;
        }
        .section {
            margin-top: 16px;
        }
        .pre {
            white-space: pre-wrap;
            word-break: break-word;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 10mm;
            }
        }
    </style>
</head>
<body>
<div class="no-print" style="margin-bottom: 12px;">
    <button onclick="window.print()">Print</button>
    <button onclick="window.close()">Close</button>
</div>

<div class="header">
    <div>
        <div class="title">Prescription</div>
        <div class="muted">Hospital Management System</div>
    </div>
    <div class="muted">
        <div>ID: {{ $prescription->prescription_id ?? $prescription->id }}</div>
        <div>Date: {{ optional($prescription->prescribed_date)->format('Y-m-d') ?? '-' }}</div>
    </div>
</div>

<table>
    <tr>
        <th>Patient</th>
        <td>{{ $prescription->patient->full_name ?? $prescription->patient->full_name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Doctor</th>
        <td>{{ $prescription->doctor->full_name ?? $prescription->doctor->full_name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Encounter Type</th>
        <td>{{ $prescription->encounter_type ?? '-' }}</td>
    </tr>
    <tr>
        <th>Encounter ID</th>
        <td>{{ $prescription->encounter_id ?? '-' }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ $prescription->status ?? '-' }}</td>
    </tr>
</table>

<div class="section">
    <h3>Diagnosis</h3>
    <div class="pre">{{ $prescription->diagnosis ?? '-' }}</div>
</div>

<div class="section">
    <h3>Medications</h3>
    @php
        $medications = $prescription->medications;
        if (is_array($medications)) {
            $medications = json_encode($medications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    @endphp
    <div class="pre">{{ $medications ?: '-' }}</div>
</div>

<div class="section">
    <h3>Instructions</h3>
    <div class="pre">{{ $prescription->instructions ?? '-' }}</div>
</div>

<div class="section">
    <h3>Notes</h3>
    <div class="pre">{{ $prescription->notes ?? '-' }}</div>
</div>

<script>
    window.addEventListener('load', () => {
        setTimeout(() => window.print(), 250);
    });
</script>
</body>
</html>
