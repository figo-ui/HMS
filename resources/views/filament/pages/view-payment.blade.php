<div class="bg-white rounded-lg overflow-hidden">
    {{-- Header with Gradient --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-white">💰 Payment Receipt</h3>
                <p class="text-indigo-100 text-sm">Transaction Details</p>
            </div>
            <div class="text-right">
                <div class="text-xs text-indigo-100">Invoice Number</div>
                <div class="text-white font-mono font-bold">{{ $payment->invoice_number }}</div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-6">
        {{-- Status Badge --}}
        <div class="flex justify-end mb-4">
            <span class="px-3 py-1 text-xs rounded-full font-semibold 
                @if($payment->serviceRequest && $payment->serviceRequest->payment_status == 'paid') bg-green-100 text-green-800
                @elseif($payment->serviceRequest && $payment->serviceRequest->payment_status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($payment->serviceRequest && $payment->serviceRequest->payment_status == 'insurance') bg-blue-100 text-blue-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ ucfirst($payment->serviceRequest->payment_status ?? 'Unknown') }}
            </span>
        </div>

        {{-- Hospital Info --}}
        <div class="text-center border-b pb-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-800">🏥 City Hospital</h2>
            <p class="text-sm text-gray-500">123 Healthcare Avenue, Medical District</p>
            <p class="text-sm text-gray-500">Phone: +1 234 567 8900 | Email: info@cityhospital.com</p>
            <p class="text-xs text-gray-400 mt-2">GST: 1234567890 | Reg No: HOSP/2024/001</p>
        </div>

        {{-- Payment Details --}}
        <div class="space-y-4">
            {{-- Patient Information --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Patient Information
                </h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Name:</span>
                        <span class="font-medium ml-2">{{ $payment->patient->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Phone:</span>
                        <span class="font-medium ml-2">{{ $payment->patient->phone ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Email:</span>
                        <span class="font-medium ml-2">{{ $payment->patient->email ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Patient ID:</span>
                        <span class="font-medium ml-2">{{ $payment->patient->id }}</span>
                    </div>
                </div>
            </div>

            {{-- Service Information --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Service Information
                </h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Service:</span>
                        <span class="font-medium ml-2">{{ $payment->serviceRequest->service->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Type:</span>
                        <span class="font-medium ml-2 capitalize">{{ $payment->serviceRequest->service->service_type ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Request #:</span>
                        <span class="font-medium ml-2">{{ $payment->serviceRequest->request_number ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Date:</span>
                        <span class="font-medium ml-2">{{ $payment->payment_date->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Breakdown --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Payment Breakdown
                </h4>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm py-1 border-b">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-semibold">${{ number_format($payment->serviceRequest->total_amount ?? 0, 2) }}</span>
                    </div>
                    
                    @if(($payment->serviceRequest->insurance_share ?? 0) > 0)
                    <div class="flex justify-between text-sm py-1 border-b">
                        <span class="text-gray-600">Insurance Coverage:</span>
                        <span class="text-green-600 font-semibold">-${{ number_format($payment->serviceRequest->insurance_share, 2) }}</span>
                    </div>
                    @endif
                    
                    @if(($payment->serviceRequest->discount ?? 0) > 0)
                    <div class="flex justify-between text-sm py-1 border-b">
                        <span class="text-gray-600">Discount:</span>
                        <span class="text-red-600 font-semibold">-${{ number_format($payment->serviceRequest->discount, 2) }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-base py-2 border-b border-gray-300">
                        <span class="font-bold text-gray-800">Patient Payable:</span>
                        <span class="font-bold text-gray-800">${{ number_format($payment->serviceRequest->patient_share ?? 0, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between text-lg py-2">
                        <span class="font-bold text-indigo-600">Amount Paid:</span>
                        <span class="font-bold text-indigo-600 text-xl">${{ number_format($payment->amount, 2) }}</span>
                    </div>
                    
                    @if(($payment->serviceRequest->patient_share - $payment->amount) > 0)
                    <div class="flex justify-between text-sm py-2 bg-yellow-50 rounded p-2">
                        <span class="text-yellow-700">Remaining Balance:</span>
                        <span class="text-yellow-700 font-semibold">${{ number_format($payment->serviceRequest->patient_share - $payment->amount, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Payment Method
                </h4>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Payment Mode:</span>
                        <span class="font-semibold capitalize flex items-center gap-1">
                            @if($payment->payment_mode == 'cash')
                                💵 Cash
                            @elseif($payment->payment_mode == 'card')
                                💳 Card
                            @elseif($payment->payment_mode == 'upi')
                                📱 UPI
                            @elseif($payment->payment_mode == 'netbanking')
                                🏦 Net Banking
                            @elseif($payment->payment_mode == 'insurance')
                                🏥 Insurance
                            @elseif($payment->payment_mode == 'mixed')
                                🔄 Mixed
                            @else
                                {{ $payment->payment_mode }}
                            @endif
                        </span>
                    </div>
                    
                    @if($payment->split_details)
                    <div class="mt-2 pl-4 border-l-2 border-indigo-200">
                        <p class="text-xs text-gray-500 mb-1">Split Details:</p>
                        @foreach($payment->split_details as $mode => $amount)
                            @if($amount > 0)
                            <div class="flex justify-between text-xs">
                                <span class="capitalize">{{ $mode }}:</span>
                                <span>${{ number_format($amount, 2) }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @endif
                    
                    @if($payment->transaction_id)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Transaction ID:</span>
                        <span class="font-mono text-xs">{{ $payment->transaction_id }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Payment Date:</span>
                        <span>{{ $payment->payment_date->format('F d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            {{-- Cashier Information --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Transaction Details
                </h4>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Collected By:</span>
                        <span class="font-medium">{{ $payment->collector->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Collection Time:</span>
                        <span>{{ $payment->created_at->format('F d, Y h:i A') }}</span>
                    </div>
                    @if($payment->remarks)
                    <div class="mt-2 pt-2 border-t">
                        <span class="text-gray-600">Remarks:</span>
                        <p class="text-sm mt-1 text-gray-700">{{ $payment->remarks }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="mt-6 pt-4 border-t text-center">
            <p class="text-xs text-gray-400">This is a computer generated receipt. Valid without signature.</p>
            <p class="text-xs text-gray-400 mt-1">Thank you for choosing City Hospital</p>
        </div>
    </div>

    {{-- Modal Footer Actions --}}
    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t">
        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print Receipt
        </button>
        <div class="text-sm text-gray-500">
            Powered by <span class="font-semibold">City Hospital HMS</span>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .modal-content, .modal-content * {
            visibility: visible;
        }
        .modal-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        button {
            display: none;
        }
    }
</style>