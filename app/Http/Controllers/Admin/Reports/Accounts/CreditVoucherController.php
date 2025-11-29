<?php

namespace App\Http\Controllers\Admin\Reports\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditVoucher;
use Carbon\Carbon;

class CreditVoucherController extends Controller
{
    public function index(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $vouchers = CreditVoucher::with(['items.headOfAccount', 'treasuryAccount', 'createdBy'])
            ->whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->orderBy('voucher_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.credit-voucher-print', compact('vouchers', 'dates'));
    }

    public function details(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $vouchers = CreditVoucher::with(['items.headOfAccount', 'treasuryAccount', 'createdBy'])
            ->whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->orderBy('voucher_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.credit-voucher-details-print', compact('vouchers', 'dates'));
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

