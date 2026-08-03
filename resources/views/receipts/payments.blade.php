<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .hospital-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .hospital-tagline {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .receipt-title {
            font-size: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        .receipt-body {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
        }
        
        .info-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }
        
        .info-label {
            color: #64748b;
            font-weight: 500;
        }
        
        .info-value {
            color: #1e293b;
            font-weight: 600;
        }
        
        .payment-breakdown {
            margin: 25px 0;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 15px;
        }
        
        .breakdown-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .breakdown-row:last-child {
            border-bottom: none;
        }
        
        .total-row {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            padding-top: 10px;
            margin-top: 10px;
            border-top: 2px solid #cbd5e1;
        }
        
        .paid-amount {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fed7aa;
            color: #92400e;
        }
        
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
        
        .print-button {
            display: block;
            width: 200px;
            margin: 20px auto 0;
            padding: 12px 24px;
            background: #4f46e5;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.3s;
        }
        
        .print-button:hover {
            background: #4338ca;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .print-button {
                display: none;
            }
            
            .receipt-header {
                background: #4f46e5;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .info-section {
                background: #f8fafc;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .payment-breakdown {
                background: #f1f5f9;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        {{-- Header --}}
        <div class="receipt-header">
            <div class="hospital-name">🏥 CITY HOSPITAL</div>
            <div class="hospital-tagline">Excellence in Healthcare</div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
        </div>
        
        {{-- Body --}}
        <div class="receipt-body">
            {{-- Invoice Info --}}
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div>
                    <div style="font-size: 12px; color: #64748b;">Invoice Number</div>
                    <div style="font-size: 18px; font-weight: bold; color: #1e293b;">{{ $payment->invoice_number }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #64748b;">Date</div>
                    <div style="font-size: 14px; font-weight: 600;">{{ $payment->payment_date->format('F d, Y') }}</div>
                </div>
            </div>
            
            {{-- Status --}}
            <div style="text-align: right; margin-bottom: 20px;">
                <span class="status-badge status-paid">✓ PAID</span>
            </div>
            
            {{-- Patient Information --}}
            <div class="info-section">
                <div class="info-title">
                    <span>👤</span> Patient Information
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $payment->patient->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $payment->patient->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Patient ID:</span>
                        <span class="info-value">#{{ $payment->patient->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $payment->patient->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            {{-- Service Information --}}
            <div class="info-section">
                <div class="info-title">
                    <span>🩺</span> Service Information
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Service:</span>
                        <span class="info-value">{{ $payment->serviceRequest->service->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Type:</span>
                        <span class="info-value capitalize">{{ $payment->serviceRequest->service->service_type ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Request #:</span>
                        <span class="info-value">{{ $payment->serviceRequest->request_number ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Request Date:</span>
                        <span class="info-value">{{ $payment->serviceRequest->requested_at->format('F d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            {{-- Payment Breakdown --}}
            <div class="payment-breakdown">
                <div class="info-title" style="margin-bottom: 15px;">
                    <span>💰</span> Payment Breakdown
                </div>
                
                <div class="breakdown-row">
                    <span>Total Amount</span>
                    <span>${{ number_format($payment->serviceRequest->total_amount ?? 0, 2) }}</span>
                </div>
                
                @if(($payment->serviceRequest->insurance_share ?? 0) > 0)
                <div class="breakdown-row">
                    <span>Insurance Coverage</span>
                    <span style="color: #10b981;">-${{ number_format($payment->serviceRequest->insurance_share, 2) }}</span>
                </div>
                @endif
                
                @if(($payment->serviceRequest->discount ?? 0) > 0)
                <div class="breakdown-row">
                    <span>Discount Applied</span>
                    <span style="color: #ef4444;">-${{ number_format($payment->serviceRequest->discount, 2) }}</span>
                </div>
                @endif
                
                <div class="breakdown-row" style="font-weight: 600;">
                    <span>Patient Payable</span>
                    <span>${{ number_format($payment->serviceRequest->patient_share ?? 0, 2) }}</span>
                </div>
                
                <div class="total-row">
                    <span>Amount Paid</span>
                    <span class="paid-amount">${{ number_format($payment->amount, 2) }}</span>
                </div>
                
                @if(($payment->serviceRequest->patient_share - $payment->amount) > 0)
                <div class="breakdown-row" style="background: #fef3c7; margin-top: 10px; padding: 8px; border-radius: 6px;">
                    <span style="color: #92400e;">Remaining Balance</span>
                    <span style="color: #92400e; font-weight: bold;">${{ number_format($payment->serviceRequest->patient_share - $payment->amount, 2) }}</span>
                </div>
                @endif
            </div>
            
            {{-- Payment Method --}}
            <div class="info-section">
                <div class="info-title">
                    <span>💳</span> Payment Method
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Payment Mode:</span>
                        <span class="info-value capitalize">
                            @if($payment->payment_mode == 'cash') 💵 Cash
                            @elseif($payment->payment_mode == 'card') 💳 Card
                            @elseif($payment->payment_mode == 'upi') 📱 UPI
                            @elseif($payment->payment_mode == 'insurance') 🏥 Insurance
                            @elseif($payment->payment_mode == 'mixed') 🔄 Mixed
                            @else {{ $payment->payment_mode }}
                            @endif
                        </span>
                    </div>
                    @if($payment->transaction_id)
                    <div class="info-item">
                        <span class="info-label">Transaction ID:</span>
                        <span class="info-value">{{ $payment->transaction_id }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Payment Date:</span>
                        <span class="info-value">{{ $payment->payment_date->format('F d, Y h:i A') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Collected By:</span>
                        <span class="info-value">{{ $payment->collector->name }}</span>
                    </div>
                </div>
                
                @if($payment->split_details)
                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">Split Payment Details:</div>
                    @foreach($payment->split_details as $mode => $amount)
                        @if($amount > 0)
                        <div class="info-item" style="margin-left: 15px;">
                            <span class="info-label capitalize">{{ $mode }}:</span>
                            <span class="info-value">${{ number_format($amount, 2) }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
                
                @if($payment->remarks)
                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">Remarks:</div>
                    <div style="font-size: 13px; color: #1e293b;">{{ $payment->remarks }}</div>
                </div>
                @endif
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="footer">
            <p>This is a computer generated receipt. Valid without signature.</p>
            <p style="margin-top: 5px;">Thank you for choosing City Hospital for your healthcare needs.</p>
            <p style="margin-top: 10px; font-size: 10px;">For any queries, please contact our billing department at billing@cityhospital.com</p>
        </div>
    </div>
    
    <button class="print-button" onclick="window.print()">
        🖨️ Print Receipt
    </button>
</body>
</html>