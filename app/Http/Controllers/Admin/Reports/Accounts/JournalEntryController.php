<?php

namespace App\Http\Controllers\Admin\Reports\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JournalEntry;
use Carbon\Carbon;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $entries = JournalEntry::with(['debits.headOfAccount', 'credits.headOfAccount', 'createdBy'])
            ->whereBetween('entry_date', [$dates['start'], $dates['end']])
            ->orderBy('entry_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.journal-entry-print', compact('entries', 'dates'));
    }

    public function details(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $entries = JournalEntry::with(['debits.headOfAccount', 'credits.headOfAccount', 'createdBy'])
            ->whereBetween('entry_date', [$dates['start'], $dates['end']])
            ->orderBy('entry_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.journal-entry-details-print', compact('entries', 'dates'));
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

