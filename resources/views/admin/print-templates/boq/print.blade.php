<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOQ - {{ $project->project_name }}</title>
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
        
        .boq-container {
            max-width: 1000px;
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
            margin-bottom: 15px;
        }
        
        .company-details h1 {
            color: #000;
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .company-details p {
            font-size: 11px;
            color: #333;
            margin: 2px 0;
        }
        
        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }
        
        .project-info {
            background: #f5f5f5;
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .project-info h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #000;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        
        .info-value {
            flex: 1;
        }
        
        .boq-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .boq-table th,
        .boq-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        .boq-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .boq-table td.text-right {
            text-align: right;
        }
        
        .boq-table td.text-center {
            text-align: center;
        }
        
        .boq-table tfoot th {
            background: #e0e0e0;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #000;
            font-size: 10px;
            text-align: center;
        }
        
        @media print {
            @page {
                margin: 10mm;
                size: A4 landscape;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .boq-container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="boq-container">
        <div class="header">
            <div class="company-info">
                <div class="company-details">
                    <h1>FDCL</h1>
                    <p>First Development Company Limited</p>
                    <p>Dhaka, Bangladesh</p>
                </div>
            </div>
            <div class="document-title">
                Bill of Quantities (BOQ)
            </div>
        </div>
        
        <div class="project-info">
            <h3>Project Information</h3>
            <div class="info-row">
                <span class="info-label">Project Name:</span>
                <span class="info-value">{{ $project->project_name }}</span>
            </div>
            @if($project->address)
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $project->address }}</span>
            </div>
            @endif
            @if($project->facing)
            <div class="info-row">
                <span class="info-label">Facing:</span>
                <span class="info-value">{{ $project->facing }}</span>
            </div>
            @endif
            @if($project->storey)
            <div class="info-row">
                <span class="info-label">Storey:</span>
                <span class="info-value">{{ $project->storey }}</span>
            </div>
            @endif
            @if($project->land_area)
            <div class="info-row">
                <span class="info-label">Land Area:</span>
                <span class="info-value">{{ number_format($project->land_area, 2) }} sq ft</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Print Date:</span>
                <span class="info-value">{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</span>
            </div>
        </div>
        
        @if($boqRecords->count() > 0)
        <table class="boq-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Head of Account</th>
                    <th style="width: 12%;" class="text-right">Planned Qty</th>
                    <th style="width: 12%;" class="text-right">Used Qty</th>
                    <th style="width: 12%;" class="text-right">Remaining Qty</th>
                    <th style="width: 12%;" class="text-right">Unit Rate</th>
                    <th style="width: 12%;" class="text-right">Planned Amount</th>
                    <th style="width: 12%;" class="text-right">Used Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($boqRecords as $index => $record)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $record->headOfAccount->account_name ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($record->planned_quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($record->used_quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($record->remaining_quantity, 2) }}</td>
                    <td class="text-right">৳{{ number_format($record->unit_rate, 2) }}</td>
                    <td class="text-right">৳{{ number_format($record->planned_amount, 2) }}</td>
                    <td class="text-right">৳{{ number_format($record->used_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">Total:</th>
                    <th class="text-right">{{ number_format($boqRecords->sum('planned_quantity'), 2) }}</th>
                    <th class="text-right">{{ number_format($boqRecords->sum('used_quantity'), 2) }}</th>
                    <th class="text-right">{{ number_format($boqRecords->sum('remaining_quantity'), 2) }}</th>
                    <th class="text-right">-</th>
                    <th class="text-right">৳{{ number_format($boqRecords->sum('planned_amount'), 2) }}</th>
                    <th class="text-right">৳{{ number_format($boqRecords->sum('used_amount'), 2) }}</th>
                </tr>
            </tfoot>
        </table>
        @else
        <div style="text-align: center; padding: 40px; color: #999;">
            <p>No BOQ records found for this project.</p>
        </div>
        @endif
        
        <div class="footer">
            <p>Generated on {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }} | FDCL - First Development Company Limited</p>
        </div>
    </div>
    
    <script>
        // Auto print when page loads (only if not in iframe, as parent's globalPrint will handle it)
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

