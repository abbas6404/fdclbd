<?php

namespace App\Livewire\Admin\FlatSales;

use Livewire\Component;
use App\Models\Customer;
use App\Models\ProjectFlat;
use App\Models\SalesAgent;
use App\Models\FlatSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // Customer fields
    public $customer_search = '';
    public $customer_id = '';
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_email = '';
    public $customer_nid = '';
    public $customer_address = '';
    
    // Seller/Sales Agent
    public $seller_search = '';
    public $seller_id = '';
    public $seller_name = '';
    
    // Flat (Multiple)
    public $flat_search = '';
    public $selected_flats = []; // Array of selected flats
    
    // Search results
    public $customer_results = [];
    public $flat_results = [];
    public $seller_results = [];
    public $active_search_type = ''; // 'customer', 'flat', 'seller'
    
    // Debounce timer
    public $search_debounce = 500;

    public function mount()
    {
        // Load recent 20 customers by default
        $this->active_search_type = 'customer';
        $this->loadRecentCustomers();
        
        // Check if flat_id is passed in query string
        if (request()->has('flat_id')) {
            $flatId = request()->get('flat_id');
            $flat = ProjectFlat::with('project')->find($flatId);
            if ($flat) {
                $this->selectFlat($flatId);
                $this->active_search_type = 'flat';
            }
        }
    }

    public function loadRecentCustomers()
    {
        $this->customer_results = Customer::orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
        $this->active_search_type = 'customer';
    }

    public function loadRecentAgents()
    {
        $this->seller_results = SalesAgent::orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
        $this->active_search_type = 'seller';
    }

    public function loadRecentFlats()
    {
        $this->flat_results = ProjectFlat::with('project')
            ->where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($flat) {
                return [
                    'id' => $flat->id,
                    'flat_number' => $flat->flat_number,
                    'flat_type' => $flat->flat_type,
                    'floor_number' => $flat->floor_number,
                    'flat_size' => $flat->flat_size,
                    'project_name' => $flat->project->project_name ?? 'N/A',
                    'status' => $flat->status,
                ];
            })
            ->toArray();
        $this->active_search_type = 'flat';
    }

    public function updatedCustomerSearch()
    {
        $this->active_search_type = 'customer';
        $this->searchCustomers();
    }

    public function updatedFlatSearch()
    {
        $this->active_search_type = 'flat';
        $this->searchFlats();
    }

    public function updatedSellerSearch()
    {
        $this->active_search_type = 'seller';
        $this->searchSellers();
    }

    public function showRecentCustomers()
    {
        $this->loadRecentCustomers();
    }

    public function showRecentAgents()
    {
        $this->loadRecentAgents();
    }

    public function showRecentFlats()
    {
        $this->loadRecentFlats();
    }

    public function searchCustomers()
    {
        if (strlen($this->customer_search) < 2) {
            // Show recent customers when search is cleared
            $this->loadRecentCustomers();
            return;
        }

        $this->customer_results = Customer::where('name', 'like', "%{$this->customer_search}%")
            ->orWhere('phone', 'like', "%{$this->customer_search}%")
            ->orWhere('email', 'like', "%{$this->customer_search}%")
            ->orWhere('nid_or_passport_number', 'like', "%{$this->customer_search}%")
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
            
        $this->dispatch('update-search-results', [
            'type' => 'customer',
            'results' => $this->customer_results
        ]);
    }

    public function searchFlats()
    {
        if (strlen($this->flat_search) < 2) {
            // Show recent flats when search is cleared
            $this->loadRecentFlats();
            return;
        }

        $this->flat_results = ProjectFlat::with('project')
            ->where('flat_number', 'like', "%{$this->flat_search}%")
            ->orWhere('flat_type', 'like', "%{$this->flat_search}%")
            ->orWhereHas('project', function($q) {
                $q->where('project_name', 'like', "%{$this->flat_search}%");
            })
            ->where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($flat) {
                return [
                    'id' => $flat->id,
                    'flat_number' => $flat->flat_number,
                    'flat_type' => $flat->flat_type,
                    'floor_number' => $flat->floor_number,
                    'flat_size' => $flat->flat_size,
                    'project_name' => $flat->project->project_name ?? 'N/A',
                    'status' => $flat->status,
                ];
            })
            ->toArray();
            
        $this->dispatch('update-search-results', [
            'type' => 'flat',
            'results' => $this->flat_results
        ]);
    }

    public function searchSellers()
    {
        if (strlen($this->seller_search) < 2) {
            // Show recent agents when search is cleared
            $this->loadRecentAgents();
            return;
        }

        $this->seller_results = SalesAgent::where('name', 'like', "%{$this->seller_search}%")
            ->orWhere('phone', 'like', "%{$this->seller_search}%")
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
            
        $this->dispatch('update-search-results', [
            'type' => 'seller',
            'results' => $this->seller_results
        ]);
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->customer_id = $customer->id;
            $this->customer_name = $customer->name;
            $this->customer_phone = $customer->phone ?? '';
            $this->customer_email = $customer->email ?? '';
            $this->customer_nid = $customer->nid_or_passport_number ?? '';
            $this->customer_address = $customer->address ?? '';
            $this->customer_search = $customer->name;
            $this->customer_results = [];
            // Keep showing recent customers after selection
            $this->loadRecentCustomers();
            
            $this->dispatch('update-search-results', [
                'type' => 'customer',
                'results' => $this->customer_results
            ]);
        }
    }

    public function selectFlat($flatId)
    {
        $flat = ProjectFlat::with('project')->find($flatId);
        if ($flat) {
            // Check if flat is already selected
            $exists = collect($this->selected_flats)->contains(function($item) use ($flatId) {
                return $item['id'] == $flatId;
            });
            if (!$exists) {
                $this->selected_flats[] = [
                    'id' => $flat->id,
                    'flat_number' => $flat->flat_number,
                    'flat_type' => $flat->flat_type,
                    'flat_size' => $flat->flat_size,
                    'floor_number' => $flat->floor_number,
                    'project_name' => $flat->project->project_name ?? 'N/A',
                    'details' => "{$flat->flat_type} - {$flat->flat_size} - Floor {$flat->floor_number} - {$flat->project->project_name}"
                ];
            }
            $this->flat_search = '';
            // Keep showing recent flats after selection
            $this->loadRecentFlats();
            
            $this->dispatch('update-search-results', [
                'type' => 'flat',
                'results' => $this->flat_results
            ]);
        }
    }
    
    public function removeFlat($flatId)
    {
        $this->selected_flats = collect($this->selected_flats)
            ->reject(function($flat) use ($flatId) {
                return $flat['id'] == $flatId;
            })
            ->values()
            ->toArray();
    }

    public function selectSeller($sellerId)
    {
        $seller = SalesAgent::find($sellerId);
        if ($seller) {
            $this->seller_id = $seller->id;
            $this->seller_name = $seller->name;
            $this->seller_search = $seller->name;
            $this->seller_results = [];
            // Keep showing recent agents after selection
            $this->loadRecentAgents();
            
            $this->dispatch('update-search-results', [
                'type' => 'seller',
                'results' => $this->seller_results
            ]);
        }
    }

    public function clearCustomer()
    {
        $this->customer_id = '';
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->customer_email = '';
        $this->customer_nid = '';
        $this->customer_address = '';
        $this->customer_search = '';
        // Reload recent customers after clearing
        $this->loadRecentCustomers();
    }

    public function clearFlat()
    {
        $this->flat_search = '';
        $this->selected_flats = [];
        // Reload recent flats after clearing
        $this->loadRecentFlats();
    }

    public function clearSeller()
    {
        $this->seller_id = '';
        $this->seller_name = '';
        $this->seller_search = '';
        // Reload recent agents after clearing
        $this->loadRecentAgents();
    }

    public function saveSale()
    {
        // Validate required fields
        $this->validate([
            'customer_name' => 'required|string|min:2',
            'customer_phone' => 'required|string|min:10',
            'customer_nid' => 'required|string|min:5',
            'selected_flats' => 'required|array|min:1',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_name.min' => 'Customer name must be at least 2 characters.',
            'customer_phone.required' => 'Customer phone is required.',
            'customer_phone.min' => 'Customer phone must be at least 10 characters.',
            'customer_nid.required' => 'NID or Passport number is required.',
            'customer_nid.min' => 'NID or Passport number must be at least 5 characters.',
            'selected_flats.required' => 'Please select at least one flat.',
            'selected_flats.min' => 'Please select at least one flat.',
        ]);

        try {
            DB::beginTransaction();

            // Create or update customer
            if ($this->customer_id) {
                $customer = Customer::find($this->customer_id);
                if ($customer) {
                    $customer->update([
                        'name' => $this->customer_name,
                        'phone' => $this->customer_phone,
                        'email' => $this->customer_email ?: null,
                        'nid_or_passport_number' => $this->customer_nid,
                        'address' => $this->customer_address ?: null,
                        'updated_by' => Auth::id(),
                    ]);
                }
            } else {
                // Create new customer
                $customer = Customer::create([
                    'name' => $this->customer_name,
                    'phone' => $this->customer_phone,
                    'email' => $this->customer_email ?: null,
                    'nid_or_passport_number' => $this->customer_nid,
                    'address' => $this->customer_address ?: null,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                $this->customer_id = $customer->id;
            }

            // Create flat sale records for each selected flat
            $saleCount = 0;
            foreach ($this->selected_flats as $selectedFlat) {
                $flat = ProjectFlat::find($selectedFlat['id']);
                
                if (!$flat) {
                    continue; // Skip if flat not found
                }

                // Check if flat is already sold
                if ($flat->status === 'sold') {
                    continue; // Skip already sold flats
                }

                // Calculate pricing (you may need to adjust this based on your pricing logic)
                // For now, using flat_size * a default price_per_sqft
                // You can add pricing fields to the form later
                $pricePerSqft = 0; // Default or get from flat pricing if available
                
                // Extract numeric value from flat_size string (e.g., "500sqft" -> 500)
                $flatSizeNumeric = 0;
                if ($flat->flat_size) {
                    // Extract numbers from the string
                    preg_match('/[\d.]+/', $flat->flat_size, $matches);
                    $flatSizeNumeric = isset($matches[0]) ? (float) $matches[0] : 0;
                }
                
                $totalPrice = $flatSizeNumeric * $pricePerSqft;
                $netPrice = $totalPrice; // Can add charges later

                // Generate sale number
                $saleNumber = FlatSale::generateSaleNumber();

                // Create flat sale record
                FlatSale::create([
                    'sale_number' => $saleNumber,
                    'customer_id' => $customer->id,
                    'flat_id' => $flat->id,
                    'sales_agent_id' => $this->seller_id ?: null,
                    'price_per_sqft' => $pricePerSqft,
                    'total_price' => $totalPrice,
                    'parking_charge' => 0,
                    'utility_charge' => 0,
                    'additional_work_charge' => 0,
                    'other_charge' => null,
                    'deduction_amount' => null,
                    'refund_amount' => null,
                    'net_price' => $netPrice,
                    'sale_date' => now()->toDateString(),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Update flat status to 'sold'
                $flat->update([
                    'status' => 'sold',
                    'updated_by' => Auth::id(),
                ]);

                $saleCount++;
            }

            DB::commit();

            if ($saleCount > 0) {
                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => "Successfully created {$saleCount} flat sale(s)!"
                ]);

                // Reset form after successful save
                $this->resetForm();
            } else {
                $this->dispatch('show-alert', [
                    'type' => 'warning',
                    'message' => 'No flats were available to sell. Some flats may already be sold.'
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving flat sale: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->customer_search = '';
        $this->customer_id = '';
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->customer_email = '';
        $this->customer_nid = '';
        $this->customer_address = '';
        $this->seller_search = '';
        $this->seller_id = '';
        $this->seller_name = '';
        $this->flat_search = '';
        $this->selected_flats = [];
        $this->customer_results = [];
        $this->flat_results = [];
        $this->seller_results = [];
        // Reload recent customers after reset
        $this->loadRecentCustomers();
    }

    public function render()
    {
        return view('livewire.admin.flat-sales.index');
    }
}

