<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Schedule - {{ $sale->sale_number }}</title>
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
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        
        .company-logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }
        
        .company-details {
            flex: 1;
        }
        
        .invoice-info {
            flex: 1;
            text-align: right;
        }
        
        .company-details h1 {
            color: #000;
            font-size: 14px;
            margin-bottom: 2px;
            font-weight: bold;
        }
        
        .company-details p {
            margin: 0;
            color: #333;
            font-size: 11px;
        }
        
        .invoice-info h2 {
            color: #000;
            font-size: 13px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .info-row {
            padding: 3px 0;
            font-size: 11px;
        }
        
        .info-label {
            font-weight: bold;
            color: #000;
            display: inline-block;
            min-width: 100px;
        }
        
        .info-value {
            color: #000;
        }
        
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .schedule-table th,
        .schedule-table td {
            border: 0.01mm solid #555;
            padding: 8px;
            text-align: left;
        }
        
        .schedule-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        
        .schedule-table td {
            font-size: 11px;
        }
        
        .schedule-table tfoot {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #000;
        }
        
        .signature-section {
            margin-top: 40px;
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
            font-size: 11px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-pending {
            background-color: #6c757d;
            color: #fff;
        }
        
        .badge-paid {
            background-color: #28a745;
            color: #fff;
        }
        
        .badge-partial {
            background-color: #ffc107;
            color: #000;
        }
        
        .no-print {
            display: none !important;
        }
        
        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .invoice-container {
                max-width: none;
                margin: 0;
                padding: 10px;
            }
            
            .no-print {
                display: none !important;
            }
            
            /* Ensure vertical line shows in print */
            .details-grid::before {
                content: '';
                position: absolute;
                left: calc(50% - 10px);
                top: 0;
                bottom: 0;
                width: 2px;
                background-color: #000 !important;
                z-index: 1;
                display: block !important;
            }
            
            .customer-info {
                padding-right: 20px !important;
            }
            
            .sale-info {
                padding-left: 20px !important;
            }
        }
    </style>
</head>
<body>
    <div style="margin: 30px;">
        <div class="invoice-container">
            <!-- Header -->
            <div class="header">
                <div class="company-info">
                    <div class="logo-section">
                        <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="company-logo" onerror="this.style.display='none'">
                    </div>
                    <div class="company-details">
                        <h1>REAL ESTATE MANAGEMENT SYSTEM</h1>
                        <p>123 Business Street, City, Country</p>
                        <p>Phone: +880-XXX-XXXXXX | Email: info@company.com</p>
                    </div>
                    <div class="invoice-info">
                        <h2>PAYMENT SCHEDULE</h2>
                        <div style="font-size: 11px; margin-top: 5px;">
                            Sale No: {{ $sale->sale_number }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer and Sale Information -->
            <div class="details-grid">
                <div class="customer-info">
                    <div class="info-row">
                        <span class="info-label">Customer Name</span>
                        <span class="info-value">: {{ $sale->customer->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">: {{ $sale->customer->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">: {{ $sale->customer->email ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address</span>
                        <span class="info-value">: {{ $sale->customer->address ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">NID</span>
                        <span class="info-value">: {{ $sale->customer->nid_or_passport_number ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="sale-info">
                    <div class="info-row">
                        <span class="info-label">Sale Date</span>
                        <span class="info-value">: {{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Flat Number</span>
                        <span class="info-value">: {{ $sale->flat->flat_number ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Flat Type</span>
                        <span class="info-value">: {{ $sale->flat->flat_type ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Flat Size</span>
                        <span class="info-value">: {{ $sale->flat->flat_size ?? 'N/A' }} sq ft</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Project</span>
                        <span class="info-value">: {{ $sale->flat->project->project_name ?? 'N/A' }}</span>
                    </div>
                    @if($sale->salesAgent)
                    <div class="info-row">
                        <span class="info-label">Sales Agent</span>
                        <span class="info-value">: {{ $sale->salesAgent->name ?? 'N/A' }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Net Price</span>
                        <span class="info-value" style="font-weight: bold;">: {{ number_format($sale->net_price ?? 0, 2) }} BDT</span>
                    </div>
                </div>
            </div>
            
            <!-- Payment Schedule Table -->
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Term Name</th>
                        <th style="width: 20%;" class="text-right">Receivable Amount</th>
                        <th style="width: 20%;" class="text-right">Received Amount</th>
                        <th style="width: 15%;" class="text-center">Due Date</th>
                        <th style="width: 5%;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $index => $schedule)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $schedule->term_name }}</td>
                        <td class="text-right">{{ number_format($schedule->receivable_amount, 2) }} BDT</td>
                        <td class="text-right">{{ number_format($schedule->received_amount ?? 0, 2) }} BDT</td>
                        <td class="text-center">{{ $schedule->due_date ? \Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y') : 'N/A' }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $schedule->status ?? 'pending' }}">
                                {{ ucfirst($schedule->status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No payment terms found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"><strong>{{ number_format($schedules->sum('receivable_amount'), 2) }} BDT</strong></td>
                        <td class="text-right"><strong>{{ number_format($schedules->sum('received_amount'), 2) }} BDT</strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

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
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
