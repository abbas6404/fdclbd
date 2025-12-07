<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Flats - {{ $project->project_name }}</title>
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
        
        .print-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
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
        
        .print-info {
            flex: 1;
            text-align: right;
        }
        
        .company-details h1 {
            color: #000;
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .company-details p {
            margin: 2px 0;
            color: #333;
            font-size: 11px;
        }
        
        .print-info h2 {
            color: #000;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .project-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .project-info-row {
            padding: 3px 0;
            font-size: 11px;
        }
        
        .info-label {
            font-weight: bold;
            color: #000;
            display: inline-block;
            min-width: 120px;
        }
        
        .info-value {
            color: #000;
        }
        
        .flats-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .flats-table th,
        .flats-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        
        .flats-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        
        .flats-table td {
            text-align: left;
        }
        
        .flats-table td.text-center {
            text-align: center;
        }
        
        .flats-table td.text-right {
            text-align: right;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-available {
            background-color: #28a745;
            color: #fff;
        }
        
        .status-sold {
            background-color: #dc3545;
            color: #fff;
        }
        
        .status-reserved {
            background-color: #ffc107;
            color: #000;
        }
        
        .status-land_owner {
            background-color: #6c757d;
            color: #fff;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .print-container {
                max-width: none;
                margin: 0;
                padding: 10px;
            }
            
            .flats-table {
                page-break-inside: auto;
            }
            
            .flats-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .flats-table thead {
                display: table-header-group;
            }
            
            .flats-table tfoot {
                display: table-footer-group;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="logo-section">
                    <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="company-logo" onerror="this.style.display='none'">
                </div>
                <div class="company-details">
                    <h1>{{ config('app.name', 'REAL ESTATE MANAGEMENT SYSTEM') }}</h1>
                    <p>123 Business Street, City, Country</p>
                    <p>Phone: +880-XXX-XXXXXX | Email: info@company.com</p>
                </div>
                <div class="print-info">
                    <h2>
                        @if(isset($statusFilter) && $statusFilter)
                            {{ strtoupper($statusFilter) }} FLATS LIST
                        @else
                            ALL FLATS LIST
                        @endif
                    </h2>
                    <div style="font-size: 11px; margin-top: 5px;">
                        Date: {{ now()->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Information -->
        <div class="project-info">
            <div class="project-info-row">
                <span class="info-label">Project Name:</span>
                <span class="info-value">{{ $project->project_name }}</span>
            </div>
            @if($project->address)
            <div class="project-info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $project->address }}</span>
            </div>
            @endif
            @if($project->land_area)
            <div class="project-info-row">
                <span class="info-label">Land Area:</span>
                <span class="info-value">{{ $project->land_area }}</span>
            </div>
            @endif
            <div class="project-info-row">
                <span class="info-label">Total Flats:</span>
                <span class="info-value"><strong>{{ $flats->count() }}</strong></span>
            </div>
        </div>
        
        <!-- Flats Table -->
        <table class="flats-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Flat Number</th>
                    <th style="width: 10%;">Type</th>
                    <th style="width: 8%;">Floor</th>
                    <th style="width: 10%;">Size (sq ft)</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 15%;">Flat Owner</th>
                    <th style="width: 12%;" class="text-right">Receive Amount</th>
                    <th style="width: 12%;" class="text-right">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flats as $index => $flat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center"><strong>{{ $flat->flat_number }}</strong></td>
                    <td class="text-center">{{ $flat->flat_type ?? 'N/A' }}</td>
                    <td class="text-center">{{ $flat->floor_number ?? 'N/A' }}</td>
                    <td class="text-center">{{ $flat->flat_size ? number_format($flat->flat_size, 2) : 'N/A' }}</td>
                    <td class="text-center">
                        <span class="status-badge status-{{ $flat->status ?? 'available' }}">
                            {{ ucfirst(str_replace('_', ' ', $flat->status ?? 'available')) }}
                        </span>
                    </td>
                    <td>
                        @if($flat->flatSales && $flat->flatSales->first() && $flat->flatSales->first()->customer)
                            <div><strong>{{ $flat->flatSales->first()->customer->name }}</strong></div>
                            @if($flat->flatSales->first()->customer->phone)
                                <div style="font-size: 10px;">{{ $flat->flatSales->first()->customer->phone }}</div>
                            @endif
                        @else
                            <span style="color: #999;">N/A</span>
                        @endif
                    </td>
                    <td class="text-right">
                        ৳{{ number_format($flat->paymentSchedules ? $flat->paymentSchedules->sum('received_amount') : 0, 0) }}
                    </td>
                    <td class="text-right">
                        @if($flat->status === 'available' && $flat->paymentSchedules && $flat->paymentSchedules->sum('receivable_amount') > 0)
                            ৳{{ number_format($flat->paymentSchedules->sum('receivable_amount'), 0) }}
                        @else
                            ৳{{ number_format($flat->paymentSchedules ? $flat->paymentSchedules->sum('receivable_amount') : 0, 0) }}
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No flats found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-right"><strong>Total:</strong></td>
                    <td class="text-right"><strong>৳{{ number_format($flats->sum(function($flat) { return $flat->paymentSchedules ? $flat->paymentSchedules->sum('received_amount') : 0; }), 0) }}</strong></td>
                    <td class="text-right"><strong>৳{{ number_format($flats->sum(function($flat) { return $flat->paymentSchedules ? $flat->paymentSchedules->sum('receivable_amount') : 0; }), 0) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
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

