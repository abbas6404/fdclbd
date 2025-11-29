<?php

namespace App\Http\Controllers\Admin\PrintControllers\PaymentSchedules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlatSale;
use App\Models\FlatSalePaymentSchedule;

class PaymentScheduleController extends Controller
{
    /**
     * Show payment schedule print template
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPaymentSchedule(Request $request)
    {
        // Get sale ID from request
        $saleId = $request->get('sale_id') ?? $request->get('sale');
        
        if (!$saleId) {
            abort(404, 'Sale ID is required');
        }

        try {
            $sale = FlatSale::with(['customer', 'flat.project', 'salesAgent'])->findOrFail($saleId);
            $schedules = FlatSalePaymentSchedule::where('flat_sale_id', $saleId)
                ->orderBy('due_date', 'asc')
                ->get();

            return view('admin.print-templates.payment-schedule', compact('sale', 'schedules'));

        } catch (\Exception $e) {
            \Log::error('Error in showPaymentSchedule: ' . $e->getMessage());
            abort(500, 'Error loading payment schedule template');
        }
    }
}

