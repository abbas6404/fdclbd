<?php

namespace App\Http\Controllers\Admin\Reports\PaymentsReceived;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentInvoice;
use Carbon\Carbon;

class PaymentsReceivedController extends Controller
{
    public function summary(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $invoices = PaymentInvoice::with(['customer', 'createdBy'])
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.reports.payments-received.summary-print', compact('invoices', 'dates'));
    }

    public function details(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $invoices = PaymentInvoice::with(['customer', 'items.paymentSchedule', 'createdBy'])
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.reports.payments-received.details-print', compact('invoices', 'dates'));
    }

    public function chequeDetails(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $invoices = PaymentInvoice::with(['customer', 'cheques', 'createdBy'])
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('payment_method', 'cheque')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.reports.payments-received.cheque-details-print', compact('invoices', 'dates'));
    }

    private function getDateRange(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        
        return [
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];
    }
}

