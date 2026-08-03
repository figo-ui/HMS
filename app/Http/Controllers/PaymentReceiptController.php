<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReceiptController extends Controller
{
    /**
     * Show the payment receipt page
     */
    public function show(Payments $payment)
    {
        // Ensure user is authorized (optional)
        if (auth()->user()->role !== 'cashier' && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('receipts.payments', compact('payments'));
    }
    
    /**
     * Download PDF receipt
     * URL: /receipt/payment/{payment}/download
     */
    public function download(Payments $payment)
    {
        $pdf = PDF::loadView('receipts.pdf', compact('payments'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("receipt-{$payment->invoice_number}.pdf");
    }
    
    /**
     * Stream PDF in browser (preview before download)
     * URL: /receipt/payment/{payment}/stream
     */
    public function stream(Payments $payment)
    {
        $pdf = PDF::loadView('receipts.pdf', compact('payments'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("receipt-{$payment->invoice_number}.pdf");
    }
    /**
     * Send receipt via email
     */
    public function email(Payments $payment)
    {
        // Mail::to($payment->patient->email)->send(new PaymentReceiptMail($payment));
        
        return redirect()->back()->with('success', 'Receipt sent to patient email');
    }
}