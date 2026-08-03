<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Receipt - {{ $payment->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: white;
            font-size: 12px;
            line-height: 1.4;
            color: #1a1a1a;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        
        /* Header Styles */
        .receipt-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #f59e0b;
        }
        
        .hospital-name {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .hospital-address {
            font-size: 10px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.3);
        }
        
        /* Content Styles */
        .receipt-body {
            padding: 30px;
        }
        
        /* Invoice Info */
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .invoice-box {
            background: #f3f4f6;
            padding: 10px 15px;
            border-radius: 8px;
        }
        
        .invoice-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }
        
        .invoice-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fed7aa;
            color: #92400e;
        }
        
        .status-insurance {
            background: #dbeafe;
            color: #1e40af;
        }
        
        /* Section Styles */
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title-icon {
            font-size: 16px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            padding: 6px 0;
            color: #6b7280;
            font-weight: 500;
            width: 35%;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 0;
            color: #1f2937;
            font-weight: 600;
        }
        
        /* Payment Breakdown Table */
        .payment-breakdown {
            margin: 25px 0;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .breakdown-header {
            background: #f3f4f6;
            padding: 10px 15px;
            font-weight: bold;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .breakdown-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .breakdown-row:last-child {
            border-bottom: none;
        }
        
        .breakdown-label {
            color: #6b7280;
        }
        
        .breakdown-value {
            font-weight: 500;
            color: #1f2937;
        }
        
        .total-row {
            background: #fef3c7;
            padding: 12px 15px;
            font-weight: bold;
            border-top: 2px solid #fbbf24;
        }
        
        .total-label {
            color: #92400e;
            font-size: 14px;
        }
        
        .total-value {
            color: #92400e;
            font-size: 16px;
        }
        
        .paid-row {
            background: #d1fae5;
            padding: 12px 15px;
            font-weight: bold;
        }
        
        .paid-label {
            color: #065f46;
            font-size: 14px;
        }
        
        .paid-value {
            color: #065f46;
            font-size: 18px;
        }
        
        .remaining-row {
            background: #fee2e2;
            padding: 10px 15px;
        }
        
        .remaining-label {
            color: #991b1b;
        }
        
        .remaining-value {
            color: #991b1b;
            font-weight: bold;
        }
        
        /* Footer */
        .receipt-footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            margin-top: 20px;
        }
        
        .footer-note {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .thankyou {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 10px;
        }
        
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #d1d5db;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-line {
            text-align: center;
            width: 200px;
        }
        
        .signature-text {
            font-size: 10px;
            color: #6b7280;
            margin-top: 5px;
        }
        
        /* Utilities */
        .text-center {
            text-align: center;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .capitalize {
            text-transform: capitalize;
        }
        
        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        {{-- Header --}}
        <div class="receipt-header">
            <div class="hospital-name">🏥 CITY HOSPITAL</div>
            <div class="hospital-address">
                123 Healthcare Avenue, Medical District<br>
                Phone: +1 234 567 8900 | Email: info@cityhospital.com
            </div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
        </div>
        
        {{-- Body --}}
        <div class="receipt-body">
            {{-- Invoice Information --}}
            <div class="invoice-info">
                <div class="invoice-box">
                    <div class="invoice-label">INVOICE NUMBER</div>
                    <div class="invoice-value">{{ $payment->invoice_number }}</div>
                </div>
                <div class="invoice-box">
                    <div class="invoice-label">DATE & TIME</div>
                    <div class="invoice-value">{{ $payment->payment_date->format('d/m/Y h:i A') }}</div>
                </div>
                <div class="invoice-box">
                    <div class="invoice-label">STATUS</div>
                    <div class="invoice-value">
                        <span class="status-badge status-paid">✓ PAID</span>
                    </div>
                </div>
            </div>
            
            {{-- Patient Information --}}
            <div class="info-section">
                <div class="section-title">
                    <span class="section-title-icon">👤</span>
                    PATIENT INFORMATION
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $payment->patient->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Patient ID</div>
                        <div class="info-value">#{{ $payment->patient->id }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value">{{ $payment->patient->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">{{ $payment->patient->email ?? 'N/A' }}</div>
                    </div>
                    @if($payment->patient->address)
                    <div class="info-row">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $payment->patient->address }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Service Information --}}
            <div class="info-section">
                <div class="section-title">
                    <span class="section-title-icon">🩺</span>
                    SERVICE INFORMATION
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Service Name</div>
                        <div class="info-value">{{ $payment->serviceRequest->service->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Service Type</div>
                        <div class="info-value capitalize">{{ $payment->serviceRequest->service->service_type ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Request Number</div>
                        <div class="info-value">{{ $payment->serviceRequest->request_number ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Request Date</div>
                        <div class="info-value">{{ $payment->serviceRequest->requested_at ? $payment->serviceRequest->requested_at->format('d/m/Y') : 'N/A' }}</div>
                    </div>
                    @if($payment->serviceRequest->service->department)
                    <div class="info-row">
                        <div class="info-label">Department</div>
                        <div class="info-value">{{ $payment->serviceRequest->service->department->name ?? 'N/A' }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Payment Breakdown --}}
            <div class="payment-breakdown">
                <div class="breakdown-header">
                    💰 PAYMENT BREAKDOWN
                </div>
                
                <div class="breakdown-row">
                    <span class="breakdown-label">Total Amount</span>
                    <span class="breakdown-value">${{ number_format($payment->serviceRequest->total_amount ?? 0, 2) }}</span>
                </div>
                
                @if(($payment->serviceRequest->insurance_share ?? 0) > 0)
                <div class="breakdown-row">
                    <span class="breakdown-label">Insurance Coverage</span>
                    <span class="breakdown-value" style="color: #10b981;">-${{ number_format($payment->serviceRequest->insurance_share, 2) }}</span>
                </div>
                @endif
                
                @if(($payment->serviceRequest->discount ?? 0) > 0)
                <div class="breakdown-row">
                    <span class="breakdown-label">Discount Applied</span>
                    <span class="breakdown-value" style="color: #ef4444;">-${{ number_format($payment->serviceRequest->discount, 2) }}</span>
                </div>
                @endif
                
                <div class="breakdown-row" style="background: #f3f4f6; font-weight: 600;">
                    <span class="breakdown-label">Patient Payable Amount</span>
                    <span class="breakdown-value">${{ number_format($payment->serviceRequest->patient_share ?? 0, 2) }}</span>
                </div>
                
                <div class="paid-row">
                    <span class="paid-label">✓ AMOUNT PAID</span>
                    <span class="paid-value">${{ number_format($payment->amount, 2) }}</span>
                </div>
                
                @if(($payment->serviceRequest->patient_share - $payment->amount) > 0)
                <div class="remaining-row">
                    <span class="remaining-label">⚠️ Remaining Balance</span>
                    <span class="remaining-value">${{ number_format($payment->serviceRequest->patient_share - $payment->amount, 2) }}</span>
                </div>
                @endif
            </div>
            
            {{-- Payment Method --}}
            <div class="info-section">
                <div class="section-title">
                    <span class="section-title-icon">💳</span>
                    PAYMENT METHOD
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Payment Mode</div>
                        <div class="info-value capitalize">
                            @if($payment->payment_mode == 'cash')
                                💵 Cash
                            @elseif($payment->payment_mode == 'card')
                                💳 Credit/Debit Card
                            @elseif($payment->payment_mode == 'upi')
                                📱 UPI / Mobile Payment
                            @elseif($payment->payment_mode == 'netbanking')
                                🏦 Net Banking
                            @elseif($payment->payment_mode == 'insurance')
                                🏥 Insurance Claim
                            @elseif($payment->payment_mode == 'mixed')
                                🔄 Mixed Payment
                            @else
                                {{ ucfirst($payment->payment_mode) }}
                            @endif
                        </div>
                    </div>
                    
                    @if($payment->transaction_id)
                    <div class="info-row">
                        <div class="info-label">Transaction ID</div>
                        <div class="info-value">{{ $payment->transaction_id }}</div>
                    </div>
                    @endif
                    
                    <div class="info-row">
                        <div class="info-label">Payment Date</div>
                        <div class="info-value">{{ $payment->payment_date->format('d/m/Y h:i A') }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Collected By</div>
                        <div class="info-value">{{ $payment->collector->name }}</div>
                    </div>
                </div>
                
                {{-- Split Payment Details --}}
                @if($payment->split_details)
                <hr>
                <div style="margin-top: 10px;">
                    <div style="font-size: 11px; font-weight: bold; color: #374151; margin-bottom: 8px;">Split Payment Details:</div>
                    @foreach($payment->split_details as $mode => $amount)
                        @if($amount > 0)
                        <div class="info-row">
                            <div class="info-label" style="padding-left: 15px;">{{ ucfirst($mode) }}</div>
                            <div class="info-value">${{ number_format($amount, 2) }}</div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
                
                {{-- Remarks --}}
                @if($payment->remarks)
                <hr>
                <div style="margin-top: 10px;">
                    <div style="font-size: 11px; font-weight: bold; color: #374151; margin-bottom: 5px;">Remarks:</div>
                    <div style="font-size: 11px; color: #6b7280; line-height: 1.4;">{{ $payment->remarks }}</div>
                </div>
                @endif
            </div>
            
            {{-- Insurance Information (if applicable) --}}
            @if($payment->serviceRequest && $payment->serviceRequest->insurance_share > 0 && $payment->patient->insurance)
            <div class="info-section" style="border-left-color: #10b981;">
                <div class="section-title">
                    <span class="section-title-icon">🏥</span>
                    INSURANCE INFORMATION
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Insurance Provider</div>
                        <div class="info-value">{{ $payment->patient->insurance->provider_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Policy Number</div>
                        <div class="info-value">{{ $payment->patient->insurance->policy_number ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Coverage Amount</div>
                        <div class="info-value">${{ number_format($payment->serviceRequest->insurance_share ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        {{-- Footer --}}
        <div class="receipt-footer">
            <div class="footer-note">
                This is a computer generated receipt. Valid without signature.
            </div>
            <div class="footer-note">
                For any queries regarding this payment, please contact billing department within 7 days.
            </div>
            <div class="thankyou">
                Thank you for choosing City Hospital for your healthcare needs!
            </div>
            
            <div class="signature">
                <div class="signature-line">
                    <div style="border-top: 1px solid #9ca3af; width: 100%; margin-top: 20px;"></div>
                    <div class="signature-text">Patient/Guardian Signature</div>
                </div>
                <div class="signature-line">
                    <div style="border-top: 1px solid #9ca3af; width: 100%; margin-top: 20px;"></div>
                    <div class="signature-text">Authorized Signature</div>
                </div>
            </div>
            
            <div style="margin-top: 20px; font-size: 8px; color: #9ca3af;">
                City Hospital | GST: 1234567890 | Reg No: HOSP/2024/001
            </div>
        </div>
    </div>
</body>
</html>