<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Report Level 3 - {{ \Carbon\Carbon::parse($dates['start'])->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dates['end'])->format('M d, Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }
        
        .container {
            width: 210mm;
            margin: 0 auto;
            padding: 10mm;
            background: #fff;
        }
        
        @media print {
            .container {
                width: 210mm;
                margin: 0;
                padding: 5mm;
            }
            body { margin: 0; padding: 0; }
            
            @page {
                margin: 0 !important;
                padding: 15mm 0 10mm 0 !important;
                size: A4;
            }
        }
        
        .report-header {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        
        .company-logo {
            width: 80px;
            height: auto;
            margin-right: 15px;
        }
        
        .company-details {
            display: flex;
            align-items: center;
            flex: 1;
        }
        
        .company-text {
            flex: 1;
        }
        
        .company-details h1 {
            color: #000;
            font-size: 18px;
            margin-bottom: 3px;
            font-weight: bold;
        }
        
        .company-details p {
            color: #333;
            font-size: 11px;
            margin: 0;
            line-height: 1.4;
        }
        
        .report-info h2 {
            color: #000;
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .date-range {
            text-align: center;
            margin: 10px 0;
            font-size: 12px;
            color: #333;
            font-weight: bold;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        
        .table th {
            background-color: #f8f9fa;
            color: #000;
            border: 1px solid #000;
            font-weight: bold;
            padding: 8px;
            text-align: center;
        }
        
        .table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .footer-info {
            font-size: 9px;
            color: #666;
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
        }
    </style>
    <script>
        // Print is triggered from parent page, no auto-print needed
    </script>
</head>
<body>
    <div class="container">
        <div class="report-header">
            <div class="company-info">
                <div class="company-details">
                    <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="company-logo">
                    <div class="company-text">
                        <h1>Formonic Design & Construction Ltd. (FDCL)</h1>
                        <p>Dhaka, Bangladesh</p>
                        <p style="font-size: 10px; color: #666;">Website: www.fdclbd.com</p>
                    </div>
                </div>
                <div class="report-info">
                    <h2>Accounts Report Level 3</h2>
                    <div class="date-range">
                        <strong>Date: {{ \Carbon\Carbon::parse($dates['start'])->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dates['end'])->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">SL</th>
                    <th style="width: 35%;">Account Name</th>
                    <th style="width: 15%;">Account Type</th>
                    <th style="width: 20%;" class="text-right">Total Debit</th>
                    <th style="width: 20%;" class="text-right">Total Credit</th>
                    <th style="width: 5%;" class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($processedAccounts as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left"><strong>{{ $item['account']->account_name }}</strong></td>
                    <td>{{ ucfirst($item['account']->account_type) }}</td>
                    <td class="text-right">{{ number_format($item['debitTotal'], 0) }}</td>
                    <td class="text-right">{{ number_format($item['creditTotal'], 0) }}</td>
                    <td class="text-right"><strong>{{ number_format($item['balance'], 0) }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No accounts with transactions found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <th colspan="3" class="text-right">Total:</th>
                    <th class="text-right">
                        {{ number_format(collect($processedAccounts)->sum('debitTotal'), 0) }}
                    </th>
                    <th class="text-right">
                        {{ number_format(collect($processedAccounts)->sum('creditTotal'), 0) }}
                    </th>
                    <th class="text-right">
                        {{ number_format(collect($processedAccounts)->sum('creditTotal') - collect($processedAccounts)->sum('debitTotal'), 0) }}
                    </th>
                </tr>
            </tfoot>
        </table>

        <div class="footer-info">
            <p>Generated Date: {{ now()->format('M d, Y \a\t h:i A') }} | Generated by: {{ auth()->user()->name ?? 'System' }}</p>
        </div>
    </div>
</body>
</html>

