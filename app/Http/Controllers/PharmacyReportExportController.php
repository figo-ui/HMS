<?php

namespace App\Http\Controllers;

use App\Services\PharmacyReportService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PharmacyReportExportController extends Controller
{
    public function csv(): StreamedResponse
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $rows = PharmacyReportService::rowsForCsv($startDate, $endDate);

        $filename = 'pharmacy-report-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Sale ID',
                'Sale Type',
                'Patient',
                'Prescription ID',
                'Medicine',
                'Quantity',
                'Unit Price',
                'Total Amount',
                'Payment Status',
                'Sold By',
                'Sold At',
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['sale_id'],
                    $row['sale_type'],
                    $row['patient'],
                    $row['prescription_id'],
                    $row['medicine_name'],
                    $row['quantity'],
                    $row['unit_price'],
                    $row['total_amount'],
                    $row['payment_status'],
                    $row['sold_by'],
                    $row['sold_at'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
