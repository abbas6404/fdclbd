<?php

namespace App\Livewire\Admin\ChequeManagement;

use Livewire\Component;
use App\Models\PaymentCheque;
use App\Models\PaymentInvoiceCheque;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // Search and filter
    public $search = '';
    public $bank_filter = '';
    public $date_from = '';
    public $date_to = '';
    
    // Data
    public $cheques = [];
    public $banks = [];
    
    // Pagination
    public $perPage = 20;
    public $currentPage = 1;
    
    // Selected cheque for details
    public $selected_cheque = null;
    public $show_details = false;

    public function mount()
    {
        $this->loadCheques();
        $this->loadBanks();
    }

    public function loadBanks()
    {
        $this->banks = PaymentCheque::select('bank_name')
            ->distinct()
            ->orderBy('bank_name')
            ->pluck('bank_name')
            ->toArray();
    }

    public function loadCheques()
    {
        $query = PaymentCheque::with(['invoiceCheques.invoice.customer', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->whereNull('deleted_at');

        // Apply search
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('cheque_number', 'like', "%{$this->search}%")
                  ->orWhere('bank_name', 'like', "%{$this->search}%");
            });
        }

        // Apply bank filter
        if (!empty($this->bank_filter)) {
            $query->where('bank_name', $this->bank_filter);
        }

        // Apply date filters
        if (!empty($this->date_from)) {
            $query->whereDate('cheque_date', '>=', $this->date_from);
        }

        if (!empty($this->date_to)) {
            $query->whereDate('cheque_date', '<=', $this->date_to);
        }

        $this->cheques = $query->get()->map(function($cheque) {
            $invoices = $cheque->invoiceCheques->map(function($ic) {
                return [
                    'invoice_number' => $ic->invoice->invoice_number ?? 'N/A',
                    'invoice_id' => $ic->invoice->id ?? null,
                    'customer_name' => $ic->invoice->customer->name ?? 'N/A',
                    'amount' => $ic->invoice->total_amount ?? 0,
                    'date' => $ic->invoice->created_at ? $ic->invoice->created_at->format('d M Y') : 'N/A',
                ];
            });

            return [
                'id' => $cheque->id,
                'cheque_number' => $cheque->cheque_number,
                'bank_name' => $cheque->bank_name,
                'cheque_amount' => $cheque->cheque_amount,
                'cheque_date' => $cheque->cheque_date ? $cheque->cheque_date->format('Y-m-d') : '',
                'cheque_date_formatted' => $cheque->cheque_date ? $cheque->cheque_date->format('d M Y') : 'N/A',
                'created_at' => $cheque->created_at ? $cheque->created_at->format('d M Y, h:i A') : 'N/A',
                'created_by' => $cheque->createdBy->name ?? 'N/A',
                'invoice_count' => $cheque->invoiceCheques->count(),
                'invoices' => $invoices->toArray(),
            ];
        })->toArray();
    }

    public function updatedSearch()
    {
        $this->loadCheques();
    }

    public function updatedBankFilter()
    {
        $this->loadCheques();
    }

    public function updatedDateFrom()
    {
        $this->loadCheques();
    }

    public function updatedDateTo()
    {
        $this->loadCheques();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->bank_filter = '';
        $this->date_from = '';
        $this->date_to = '';
        $this->loadCheques();
    }

    public function viewDetails($chequeId)
    {
        $cheque = PaymentCheque::with(['invoiceCheques.invoice.customer', 'createdBy'])
            ->find($chequeId);
        
        if ($cheque) {
            $this->selected_cheque = [
                'id' => $cheque->id,
                'cheque_number' => $cheque->cheque_number,
                'bank_name' => $cheque->bank_name,
                'cheque_amount' => $cheque->cheque_amount,
                'cheque_date' => $cheque->cheque_date ? $cheque->cheque_date->format('d M Y') : 'N/A',
                'created_at' => $cheque->created_at ? $cheque->created_at->format('d M Y, h:i A') : 'N/A',
                'created_by' => $cheque->createdBy->name ?? 'N/A',
                'invoices' => $cheque->invoiceCheques->map(function($ic) {
                    return [
                        'invoice_number' => $ic->invoice->invoice_number ?? 'N/A',
                        'invoice_id' => $ic->invoice->id ?? null,
                        'customer_name' => $ic->invoice->customer->name ?? 'N/A',
                        'amount' => $ic->invoice->total_amount ?? 0,
                        'date' => $ic->invoice->created_at ? $ic->invoice->created_at->format('d M Y') : 'N/A',
                    ];
                })->toArray(),
            ];
            $this->show_details = true;
        }
    }

    public function closeDetails()
    {
        $this->show_details = false;
        $this->selected_cheque = null;
    }

    public function deleteCheque($chequeId)
    {
        try {
            $cheque = PaymentCheque::find($chequeId);
            if ($cheque) {
                // Check if cheque is used in any invoices
                $invoiceCount = $cheque->invoiceCheques->count();
                if ($invoiceCount > 0) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => "Cannot delete cheque. It is used in {$invoiceCount} invoice(s)."
                    ]);
                    return;
                }

                $cheque->delete();

                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => 'Cheque deleted successfully.'
                ]);

                $this->loadCheques();
            }
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting cheque: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.cheque-management.index');
    }
}
