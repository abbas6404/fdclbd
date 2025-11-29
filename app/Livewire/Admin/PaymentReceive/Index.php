<?php

namespace App\Livewire\Admin\PaymentReceive;

use Livewire\Component;
use App\Models\Customer;
use App\Models\FlatSale;
use App\Models\FlatSalePaymentSchedule;
use App\Models\PaymentInvoice;
use App\Models\PaymentInvoiceItem;
use App\Models\PaymentCheque;
use App\Models\PaymentInvoiceCheque;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // Search fields
    public $customer_search = '';
    public $customer_results = [];
    public $selected_customer_id = '';
    public $selected_customer = null;
    public $active_search_type = 'recent'; // 'recent' or 'search'
    
    // Payment schedule items
    public $pending_schedules = [];
    public $selected_schedules = []; // Array of schedule IDs with payment amounts
    public $show_all_schedules = false; // Toggle to show all schedules or only pending
    
    // Payment form
    public $total_payment_amount = 0;
    public $payment_method = 'cash';
    public $remark = '';
    
    // Cheque information (for multiple cheques)
    public $cheques = []; // Array of cheque information with editable rows
    
    // Invoice summary
    public $invoice_number = '';

    public function mount()
    {
        // Load recent customers based on due date by default
        $this->loadRecentCustomers();
    }

    public function loadRecentCustomers()
    {
        // Get customers with payment schedules, ordered by due date (earliest first)
        // Using a more efficient query with joins
        $customers = DB::table('customers')
            ->join('flat_sales', 'customers.id', '=', 'flat_sales.customer_id')
            ->join('flat_sale_payment_schedules', 'flat_sales.id', '=', 'flat_sale_payment_schedules.flat_sale_id')
            ->whereIn('flat_sale_payment_schedules.status', ['pending', 'partial'])
            ->whereNotNull('flat_sale_payment_schedules.due_date')
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone',
                'customers.email',
                'customers.nid_or_passport_number',
                DB::raw('MAX(flat_sale_payment_schedules.due_date) as most_recent_due_date')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.phone', 'customers.email', 'customers.nid_or_passport_number')
            ->orderBy('most_recent_due_date', 'asc')
            ->limit(20)
            ->get()
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone ?? 'N/A',
                    'email' => $customer->email ?? 'N/A',
                    'nid_or_passport_number' => $customer->nid_or_passport_number ?? 'N/A',
                    'most_recent_due_date' => $customer->most_recent_due_date,
                ];
            })
            ->toArray();
        
        $this->customer_results = $customers;
        $this->active_search_type = 'recent';
    }

    public function showRecentCustomers()
    {
        $this->loadRecentCustomers();
    }

    public function updatedCustomerSearch()
    {
        if (strlen($this->customer_search) < 2) {
            // If search is empty, show recent customers
            $this->loadRecentCustomers();
            return;
        }

        $this->active_search_type = 'search';
        $this->customer_results = Customer::where('name', 'like', "%{$this->customer_search}%")
            ->orWhere('phone', 'like', "%{$this->customer_search}%")
            ->orWhere('email', 'like', "%{$this->customer_search}%")
            ->orWhere('nid_or_passport_number', 'like', "%{$this->customer_search}%")
            ->select('id', 'name', 'phone', 'email', 'nid_or_passport_number')
            ->limit(10)
            ->get()
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone ?? 'N/A',
                    'email' => $customer->email ?? 'N/A',
                    'nid_or_passport_number' => $customer->nid_or_passport_number ?? 'N/A',
                ];
            })
            ->toArray();
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            // Get first flat number from customer's flat sales
            $firstFlatSale = FlatSale::where('customer_id', $customer->id)
                ->with('flat')
                ->first();
            
            $this->selected_customer_id = $customer->id;
            $this->selected_customer = [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone ?? 'N/A',
                'email' => $customer->email ?? 'N/A',
                'flat_number' => $firstFlatSale && $firstFlatSale->flat ? $firstFlatSale->flat->flat_number : 'N/A',
            ];
            $this->customer_search = $customer->name;
            $this->customer_results = [];
            
            // Load pending payment schedules
            $this->loadPendingSchedules();
        }
    }

    public function loadPendingSchedules()
    {
        if ($this->selected_customer_id) {
            $query = FlatSalePaymentSchedule::with(['flatSale.flat'])
                ->whereHas('flatSale', function($q) {
                    $q->where('customer_id', $this->selected_customer_id);
                });
            
            // Filter by status if not showing all schedules
            if (!$this->show_all_schedules) {
                $query->where(function($q) {
                    $q->where('status', 'pending')
                      ->orWhere('status', 'partial');
                });
            }
            
            $schedules = $query->orderBy('due_date', 'asc')
                ->get()
                ->map(function($schedule) {
                    $remaining = $schedule->receivable_amount - ($schedule->received_amount ?? 0);
                    return [
                        'id' => $schedule->id,
                        'flat_sale_id' => $schedule->flat_sale_id,
                        'sale_number' => $schedule->flatSale->sale_number ?? 'N/A',
                        'flat_number' => $schedule->flatSale->flat->flat_number ?? 'N/A',
                        'term_name' => $schedule->term_name,
                        'receivable_amount' => $schedule->receivable_amount,
                        'received_amount' => $schedule->received_amount ?? 0,
                        'remaining_amount' => $remaining,
                        'due_date' => $schedule->due_date ? $schedule->due_date->format('Y-m-d') : '',
                        'status' => $schedule->status,
                    ];
                })
                ->toArray();
            
            $this->pending_schedules = $schedules;
            $this->selected_schedules = [];
        }
    }

    public function toggleSchedule($scheduleId)
    {
        $schedule = collect($this->pending_schedules)->firstWhere('id', $scheduleId);
        if (!$schedule) return;

        $existingIndex = collect($this->selected_schedules)->search(function($item) use ($scheduleId) {
            return $item['schedule_id'] == $scheduleId;
        });

        if ($existingIndex !== false) {
            // Remove from selected
            unset($this->selected_schedules[$existingIndex]);
            $this->selected_schedules = array_values($this->selected_schedules);
        } else {
            // Add to selected with remaining amount as default
            $this->selected_schedules[] = [
                'schedule_id' => $scheduleId,
                'amount' => $schedule['remaining_amount'],
            ];
        }
        
        $this->calculateTotal();
    }

    public function toggleAllSchedules()
    {
        // This method is for select all checkbox (can be implemented later)
        // For now, just return
    }

    public function updatedSelectedSchedules($value, $key)
    {
        // When any schedule amount is updated, recalculate total
        if (str_contains($key, '.amount')) {
            $index = (int) explode('.', $key)[0];
            if (isset($this->selected_schedules[$index])) {
                $scheduleId = $this->selected_schedules[$index]['schedule_id'];
                $schedule = collect($this->pending_schedules)->firstWhere('id', $scheduleId);
                
                if ($schedule) {
                    // Validate amount doesn't exceed remaining
                    $maxAmount = $schedule['remaining_amount'];
                    $this->selected_schedules[$index]['amount'] = min((int) $value, $maxAmount);
                }
            }
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->total_payment_amount = collect($this->selected_schedules)->sum('amount');
    }

    public function addEmptyCheque()
    {
        // Add an empty cheque row for inline editing
        $this->cheques[] = [
            'cheque_number' => '',
            'bank_name' => '',
            'cheque_amount' => '',
            'cheque_date' => '',
        ];
        
        // Automatically set payment method to cheque when adding a cheque row
        $this->payment_method = 'cheque';
    }

    public function updateCheque($index, $field, $value)
    {
        if (isset($this->cheques[$index])) {
            if ($field === 'cheque_amount') {
                $this->cheques[$index][$field] = $value ? (int) $value : '';
            } else {
                $this->cheques[$index][$field] = $value;
            }
        }
    }

    public function removeCheque($index)
    {
        unset($this->cheques[$index]);
        $this->cheques = array_values($this->cheques); // Re-index array
    }

    public function updatedPaymentMethod()
    {
        // Clear cheques when payment method changes
        if ($this->payment_method !== 'cheque') {
            $this->cheques = [];
        }
    }

    public function receivePayment()
    {
        if (!$this->selected_customer_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a customer first.'
            ]);
            return;
        }

        if (count($this->selected_schedules) === 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select at least one payment schedule.'
            ]);
            return;
        }

        // Validate payment method and amount
        $validationRules = [
            'payment_method' => 'required|in:cash,bank_transfer,cheque,card,mobile_banking',
            'total_payment_amount' => 'required|numeric|min:1',
        ];

        $validationMessages = [
            'payment_method.required' => 'Payment method is required.',
            'total_payment_amount.required' => 'Total payment amount is required.',
            'total_payment_amount.min' => 'Payment amount must be greater than 0.',
        ];

        // If payment method is cheque, validate cheques
        if ($this->payment_method === 'cheque') {
            if (count($this->cheques) === 0) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Please add at least one cheque.'
                ]);
                return;
            }

            // Validate all cheque fields are filled
            foreach ($this->cheques as $index => $cheque) {
                if (empty($cheque['cheque_number']) || empty($cheque['bank_name']) || empty($cheque['cheque_amount']) || empty($cheque['cheque_date'])) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => 'Please fill in all required fields for all cheques.'
                    ]);
                    return;
                }
            }

            // Check for duplicate cheque numbers within the form
            $chequeNumbers = collect($this->cheques)->pluck('cheque_number')->filter();
            if ($chequeNumbers->count() !== $chequeNumbers->unique()->count()) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Duplicate cheque numbers are not allowed.'
                ]);
                return;
            }

            // Check if cheque numbers already exist in the database
            $existingCheques = PaymentCheque::whereIn('cheque_number', $chequeNumbers->toArray())
                ->whereNull('deleted_at')
                ->pluck('cheque_number')
                ->toArray();
            
            if (!empty($existingCheques)) {
                $duplicateNumbers = implode(', ', $existingCheques);
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => "The following cheque number(s) already exist in the database: {$duplicateNumbers}. Please use different cheque numbers."
                ]);
                return;
            }

            // Validate total cheque amount matches payment amount
            $totalChequeAmount = collect($this->cheques)->sum(function($cheque) {
                return is_numeric($cheque['cheque_amount']) ? (int) $cheque['cheque_amount'] : 0;
            });
            if ($totalChequeAmount != $this->total_payment_amount) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Total cheque amount must match the payment amount.'
                ]);
                return;
            }
        }

        $this->validate($validationRules, $validationMessages);

        try {
            DB::beginTransaction();

            // Generate invoice number
            $invoiceNumber = PaymentInvoice::generateInvoiceNumber();

            // Create payment invoice
            $invoice = PaymentInvoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $this->selected_customer_id,
                'total_amount' => $this->total_payment_amount,
                'payment_method' => $this->payment_method,
                'remark' => $this->remark ?: null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Create invoice items and update payment schedules
            foreach ($this->selected_schedules as $selected) {
                $schedule = FlatSalePaymentSchedule::find($selected['schedule_id']);
                if (!$schedule) continue;

                $paymentAmount = (int) $selected['amount'];
                $newReceivedAmount = ($schedule->received_amount ?? 0) + $paymentAmount;
                $receivableAmount = $schedule->receivable_amount;

                // Determine status
                $status = 'paid';
                if ($newReceivedAmount < $receivableAmount) {
                    $status = 'partial';
                }

                // Update payment schedule
                $schedule->update([
                    'received_amount' => $newReceivedAmount,
                    'status' => $status,
                    'updated_by' => Auth::id(),
                ]);

                // Create invoice item
                PaymentInvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'payment_schedule_id' => $schedule->id,
                    'amount' => $paymentAmount,
                ]);
            }

            // If payment method is cheque, create cheque records
            if ($this->payment_method === 'cheque' && count($this->cheques) > 0) {
                foreach ($this->cheques as $chequeData) {
                    // Check if cheque number already exists in database
                    $existingCheque = PaymentCheque::where('cheque_number', $chequeData['cheque_number'])->first();
                    
                    if ($existingCheque) {
                        // Use existing cheque
                        $cheque = $existingCheque;
                    } else {
                        // Create new cheque
                        $cheque = PaymentCheque::create([
                            'cheque_number' => $chequeData['cheque_number'],
                            'bank_name' => $chequeData['bank_name'],
                            'cheque_amount' => $chequeData['cheque_amount'],
                            'cheque_date' => $chequeData['cheque_date'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                    }

                    // Link cheque to invoice
                    PaymentInvoiceCheque::create([
                        'cheque_id' => $cheque->id,
                        'payment_invoice_id' => $invoice->id,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => "Payment received successfully! Invoice #{$invoiceNumber}"
            ]);

            // Reload schedules and reset form
            $this->loadPendingSchedules();
            $this->selected_schedules = [];
            $this->total_payment_amount = 0;
            $this->payment_method = 'cash';
            $this->remark = '';
            $this->cheques = [];

            return $invoice;

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error receiving payment: ' . $e->getMessage()
            ]);
            return null;
        }
    }

    public function saveAndPrint()
    {
        $invoice = $this->receivePayment();
        
        if ($invoice) {
            // Trigger print event with invoice ID
            $this->dispatch('print-payment-invoice', invoice_id: $invoice->id);
        }
    }

    public function toggleShowAllSchedules()
    {
        $this->show_all_schedules = !$this->show_all_schedules;
        if ($this->selected_customer_id) {
            $this->loadPendingSchedules();
        }
    }

    public function clearCustomer()
    {
        $this->selected_customer_id = '';
        $this->selected_customer = null;
        $this->customer_search = '';
        $this->pending_schedules = [];
        $this->selected_schedules = [];
        $this->total_payment_amount = 0;
        $this->show_all_schedules = false;
        // Reload recent customers after clearing
        $this->loadRecentCustomers();
    }

    public function render()
    {
        return view('livewire.admin.payment-receive.index');
    }
}

