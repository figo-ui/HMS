<?php

use App\Http\Controllers\PrescriptionPrintController;
use App\Http\Controllers\PharmacyReportExportController;
use App\Http\Controllers\PharmacyReportPrintController;
use App\Http\Controllers\UserPrintController;
use App\Http\Controllers\PaymentReceiptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->get('/admin/prescriptions/{prescription}/print', PrescriptionPrintController::class)
    ->name('prescriptions.print');

Route::middleware(['auth'])->get('/admin/users/{user}/print', UserPrintController::class)
    ->name('users.print');

Route::middleware(['auth'])->get('/admin/pharmacy-reports/export/csv', [PharmacyReportExportController::class, 'csv'])
    ->name('pharmacy-reports.export.csv');
Route::middleware(['auth'])->get('/admin/pharmacy-reports/print', PharmacyReportPrintController::class)
    ->name('pharmacy-reports.print');
Route::middleware(['auth'])->group(function () {

    // ========== RECEIPT ROUTES ==========
    // HTML View Receipt (opens in browser)
    Route::get('/receipts/payments/{payments}', [PaymentReceiptController::class, 'show'])
        ->name('receipts.payments.show');

    // Download PDF Receipt
    Route::get('/receipts/payments/{payment}/download', [PaymentReceiptController::class, 'download'])
        ->name('payments.receipts.download');

    // Stream PDF in Browser (for preview)
    Route::get('/receipt/payment/{payment}/stream', [PaymentReceiptController::class, 'stream'])
        ->name('payment.receipt.stream');


    // Bulk Print Receipts
    Route::post('/receipt/payment/bulk-print', [PaymentReceiptController::class, 'bulkPrint'])
        ->name('payment.receipt.bulk-print');

    // ========== Alternative: API Routes for Mobile ==========
    Route::prefix('api')->group(function () {
        Route::get('/receipt/{invoice_number}', [PaymentReceiptController::class, 'apiShow'])
            ->name('api.receipt.show');
        Route::get('/receipt/{payment}/pdf', [PaymentReceiptController::class, 'apiDownload'])
            ->name('api.receipt.download');
    });
});
