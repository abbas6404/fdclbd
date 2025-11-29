<?php

namespace App\Http\Controllers\Admin\PrintControllers\PaymentInvoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentInvoice;

class PaymentInvoiceController extends Controller
{
    /**
     * Show payment invoice print template
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPaymentInvoice(Request $request)
    {
        // Get invoice ID from request
        $invoiceId = $request->get('invoice_id') ?? $request->get('invoice');
        
        if (!$invoiceId) {
            abort(404, 'Invoice ID is required');
        }

        try {
            $invoice = PaymentInvoice::with([
                'customer',
                'items.paymentSchedule.flatSale.flat.project',
                'invoiceCheques.cheque',
                'createdBy'
            ])->findOrFail($invoiceId);

            return view('admin.print-templates.payment-invoice', compact('invoice'));

        } catch (\Exception $e) {
            \Log::error('Error in showPaymentInvoice: ' . $e->getMessage());
            abort(500, 'Error loading payment invoice template');
        }
    }
}

