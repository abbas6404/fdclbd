<?php

namespace App\Http\Controllers\Admin\Reports\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DebitVoucher;
use Carbon\Carbon;

class DebitVoucherController extends Controller
{
    public function index(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $vouchers = DebitVoucher::with(['items.headOfAccount', 'treasuryAccount', 'createdBy'])
            ->whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->orderBy('voucher_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.debit-voucher-print', compact('vouchers', 'dates'));
    }

    public function details(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $vouchers = DebitVoucher::with(['items.headOfAccount', 'treasuryAccount', 'createdBy'])
            ->whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->orderBy('voucher_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.debit-voucher-details-print', compact('vouchers', 'dates'));
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

