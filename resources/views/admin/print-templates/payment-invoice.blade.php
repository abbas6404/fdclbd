<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice - {{ $invoice->invoice_number }}</title>
    <script>
        // Override any parent page title
        if (window.parent !== window) {
            window.parent.document.title = '';
        }
        document.title = '';
        
        // Hide any URL display elements
        document.addEventListener('DOMContentLoaded', function() {
            const urlElements = document.querySelectorAll('[class*="url"], [class*="address"], [class*="browser"]');
            urlElements.forEach(el => el.remove());
            
            const style = document.createElement('style');
            style.textContent = `
                body::before, body::after { display: none !important; }
                .url-display, .browser-url, .address-bar { display: none !important; }
                
                @media print {
                    @page {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    body {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            text-align: center;
        }
        
        .header h2 {
            font-size: 18px;
            font-weight: normal;
            color: #333;
            text-align: center;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
        }
        
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        
        .info-value {
            width: 60%;
            text-align: right;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }
        
        .items-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .items-table tfoot {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .items-table tfoot td {
            padding: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #000;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        
        .cheque-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        
        .cheque-section h3 {
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .cheque-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .cheque-table th,
        .cheque-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }
        
        .cheque-table th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            @page {
                margin: 15mm;
                size: A4;
            }
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1>Payment Invoice</h1>
            <h2>Payment Receipt</h2>
        </div>
        
        <!-- Invoice Information -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Invoice Number:</span>
                <span class="info-value">#{{ $invoice->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Invoice Date:</span>
                <span class="info-value">{{ $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, h:i A') : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Customer Name:</span>
                <span class="info-value">{{ $invoice->customer->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Customer Phone:</span>
                <span class="info-value">{{ $invoice->customer->phone ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Customer Email:</span>
                <span class="info-value">{{ $invoice->customer->email ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Customer Address:</span>
                <span class="info-value">{{ $invoice->customer->address ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</span>
            </div>
            @if($invoice->remark)
            <div class="info-row">
                <span class="info-label">Remark:</span>
                <span class="info-value">{{ $invoice->remark }}</span>
            </div>
            @endif
        </div>
        
        <!-- Payment Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Sale Number</th>
                    <th style="width: 20%;">Flat Number</th>
                    <th style="width: 25%;">Term Name</th>
                    <th style="width: 25%;" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->paymentSchedule->flatSale->sale_number ?? 'N/A' }}</td>
                    <td>{{ $item->paymentSchedule->flatSale->flat->flat_number ?? 'N/A' }}</td>
                    <td>{{ $item->paymentSchedule->term_name ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }} BDT</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No items found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->total_amount, 2) }} BDT</strong></td>
                </tr>
            </tfoot>
        </table>
        
        <!-- Cheque Information (if payment method is cheque) -->
        @if($invoice->payment_method === 'cheque' && $invoice->invoiceCheques->count() > 0)
        <div class="cheque-section">
            <h3>Cheque Details</h3>
            <table class="cheque-table">
                <thead>
                    <tr>
                        <th>Cheque Number</th>
                        <th>Bank Name</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->invoiceCheques as $invoiceCheque)
                    <tr>
                        <td>{{ $invoiceCheque->cheque->cheque_number ?? 'N/A' }}</td>
                        <td>{{ $invoiceCheque->cheque->bank_name ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($invoiceCheque->cheque->cheque_amount ?? 0, 2) }} BDT</td>
                        <td class="text-center">{{ $invoiceCheque->cheque->cheque_date ? \Carbon\Carbon::parse($invoiceCheque->cheque->cheque_date)->format('d M Y') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">Total Cheque Amount:</th>
                        <th class="text-right">{{ number_format($invoice->invoiceCheques->sum(function($ic) { return $ic->cheque->cheque_amount ?? 0; }), 2) }} BDT</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">
                        Customer Signature
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        Authorized Signature
                    </div>
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px; font-size: 10px; color: #666;">
                <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
                @if($invoice->createdBy)
                <p>Prepared by: {{ $invoice->createdBy->name ?? 'N/A' }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads (only if not in iframe, as parent will handle it)
        window.onload = function() {
            // Check if we're in an iframe
            if (window.self === window.top) {
                // Not in iframe, so auto-print
                setTimeout(function() {
                    window.print();
                }, 500);
            }
            // If in iframe, the parent's globalPrint function will handle printing
        };
    </script>
</body>
</html>

