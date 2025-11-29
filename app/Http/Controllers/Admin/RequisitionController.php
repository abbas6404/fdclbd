<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    /**
     * Display the requisitions page.
     */
    public function index()
    {
        return view('admin.requisitions.index');
    }

    /**
     * Display the requisition confirmation page.
     */
    public function confirm()
    {
        return view('admin.requisitions.confirm');
    }

    /**
     * Approve a requisition.
     */
    public function approve(Request $request, $id)
    {
        $requisition = \App\Models\Requisition::findOrFail($id);
        
        if ($requisition->status !== 'pending') {
            return back()->with('alert_type', 'error')
                        ->with('alert_message', 'Only pending requisitions can be approved.');
        }

        try {
            $requisition->update([
                'status' => 'approved',
                'updated_by' => auth()->id(),
            ]);

            return back()->with('alert_type', 'success')
                        ->with('alert_message', "Requisition {$requisition->requisition_number} approved successfully!");
        } catch (\Exception $e) {
            return back()->with('alert_type', 'error')
                        ->with('alert_message', 'Error approving requisition: ' . $e->getMessage());
        }
    }

    /**
     * Reject a requisition.
     */
    public function reject(Request $request, $id)
    {
        $requisition = \App\Models\Requisition::findOrFail($id);
        
        if ($requisition->status !== 'pending') {
            return back()->with('alert_type', 'error')
                        ->with('alert_message', 'Only pending requisitions can be rejected.');
        }

        try {
            $requisition->update([
                'status' => 'rejected',
                'updated_by' => auth()->id(),
            ]);

            return back()->with('alert_type', 'success')
                        ->with('alert_message', "Requisition {$requisition->requisition_number} rejected.");
        } catch (\Exception $e) {
            return back()->with('alert_type', 'error')
                        ->with('alert_message', 'Error rejecting requisition: ' . $e->getMessage());
        }
    }
}
