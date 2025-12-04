<?php

namespace App\Livewire\Admin\FlatSales;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Customer;
use App\Models\ProjectFlat;
use App\Models\Project;
use App\Models\SalesAgent;
use App\Models\FlatSale;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    // Customer fields
    public $customer_search = '';
    public $customer_id = '';
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_email = '';
    public $customer_nid = '';
    public $customer_address = '';
    
    // Nominee Information
    public $nominee_name = '';
    public $nominee_nid = '';
    public $nominee_phone = '';
    public $nominee_relationship = '';
    public $nominee_relationship_other = '';
    
    // Seller/Sales Agent
    public $seller_search = '';
    public $seller_id = '';
    public $seller_name = '';
    public $seller_phone = '';
    public $seller_nid = '';
    
    // Project search
    public $project_search = '';
    public $selected_project_id = '';
    public $selected_project = null;
    
    // Flat (Multiple)
    public $flat_search = '';
    public $selected_flats = []; // Array of selected flats
    
    // Search results
    public $customer_results = [];
    public $project_results = [];
    public $flat_results = [];
    public $seller_results = [];
    public $active_search_type = ''; // 'customer', 'flat', 'seller', 'project'
    public $show_customer_dropdown = false;
    public $show_project_dropdown = false;
    public $show_seller_dropdown = false;
    public $show_flat_dropdown = false;
    
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

    public function loadRecentProjects()
    {
        $this->project_results = Project::select('id', 'project_name', 'address')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
        $this->active_search_type = 'project';
    }

    public function loadRecentFlats()
    {
        $query = ProjectFlat::with('project')
            ->where('status', 'available');
            
        // Filter by selected project if any
        if ($this->selected_project_id) {
            $query->where('project_id', $this->selected_project_id);
        }
        
        $this->flat_results = $query
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
                    'project_id' => $flat->project_id,
                    'status' => $flat->status,
                ];
            })
            ->toArray();
        $this->active_search_type = 'flat';
    }

    public function updatedCustomerSearch()
    {
        $this->show_customer_dropdown = true;
        $this->active_search_type = 'customer';
        $this->searchCustomers();
    }

    public function updatedProjectSearch()
    {
        $this->show_project_dropdown = true;
        $this->active_search_type = 'project';
        $this->searchProjects();
    }

    public function updatedFlatSearch()
    {
        $this->show_flat_dropdown = true;
        $this->active_search_type = 'flat';
        $this->searchFlats();
    }

    public function updatedSellerSearch()
    {
        $this->show_seller_dropdown = true;
        $this->active_search_type = 'seller';
        $this->searchSellers();
    }

    public function showRecentCustomers()
    {
        $this->show_customer_dropdown = true;
        $this->loadRecentCustomers();
    }

    public function showRecentAgents()
    {
        $this->show_seller_dropdown = true;
        $this->loadRecentAgents();
    }

    public function showRecentProjects()
    {
        $this->show_project_dropdown = true;
        $this->loadRecentProjects();
    }

    public function showRecentFlats()
    {
        $this->show_flat_dropdown = true;
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

    public function searchProjects()
    {
        if (strlen($this->project_search) < 2) {
            $this->loadRecentProjects();
            return;
        }

        $this->project_results = Project::select('id', 'project_name', 'address')
            ->where('project_name', 'like', "%{$this->project_search}%")
            ->orWhere('address', 'like', "%{$this->project_search}%")
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
    }

    public function searchFlats()
    {
        if (strlen($this->flat_search) < 2) {
            // Show recent flats when search is cleared
            $this->loadRecentFlats();
            return;
        }

        $query = ProjectFlat::with('project')
            ->where(function($q) {
                $q->where('flat_number', 'like', "%{$this->flat_search}%")
            ->orWhere('flat_type', 'like', "%{$this->flat_search}%")
                  ->orWhere('floor_number', 'like', "%{$this->flat_search}%");
            })
            ->where('status', 'available');
            
        // Filter by selected project if any
        if ($this->selected_project_id) {
            $query->where('project_id', $this->selected_project_id);
        }

        $this->flat_results = $query
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
                    'project_id' => $flat->project_id,
                    'status' => $flat->status,
                ];
            })
            ->toArray();
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
            $this->show_customer_dropdown = false;
        }
    }

    public $selected_flat_id = '';
    public $selected_flat = null;
    
    // Document attachments
    public $attachments = [];
    public $tempFiles = [];

    public function selectFlat($flatId)
    {
        $flat = ProjectFlat::with('project')->find($flatId);
        if ($flat) {
            // Check if flat is already selected
            $exists = collect($this->selected_flats)->contains(function($item) use ($flatId) {
                return $item['id'] == $flatId;
            });
            if (!$exists) {
                $this->selected_flat_id = $flat->id;
                $this->selected_flat = [
                    'id' => $flat->id,
                    'flat_number' => $flat->flat_number,
                    'flat_type' => $flat->flat_type,
                    'flat_size' => $flat->flat_size,
                    'floor_number' => $flat->floor_number,
                    'project_name' => $flat->project->project_name ?? 'N/A',
                ];
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
            $this->show_flat_dropdown = false;
        }
    }
    
    public function clearSelectedFlat()
    {
        $this->selected_flat_id = '';
        $this->selected_flat = null;
        $this->flat_search = '';
    }

    public function addAttachment()
    {
        $this->attachments[] = [
            'document_name' => '',
            'file' => null,
        ];
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
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
            $this->seller_phone = $seller->phone ?? '';
            $this->seller_nid = $seller->nid_or_passport_number ?? '';
            $this->seller_search = $seller->name;
            $this->seller_results = [];
            $this->show_seller_dropdown = false;
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

    public function selectProject($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $this->selected_project_id = $project->id;
            $this->selected_project = [
                'id' => $project->id,
                'project_name' => $project->project_name,
                'address' => $project->address,
            ];
            $this->project_search = $project->project_name;
            $this->project_results = [];
            $this->show_project_dropdown = false;
            // Load flats for selected project
            $this->loadRecentFlats();
        }
    }

    public function clearProject()
    {
        $this->selected_project_id = '';
        $this->selected_project = null;
        $this->project_search = '';
        $this->show_project_dropdown = false;
        // Reload all flats after clearing
        $this->loadRecentFlats();
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
        $this->seller_phone = '';
        $this->seller_nid = '';
        $this->seller_search = '';
        // Reload recent agents after clearing
        $this->loadRecentAgents();
    }

    public function closeAllDropdowns()
    {
        $this->show_customer_dropdown = false;
        $this->show_project_dropdown = false;
        $this->show_seller_dropdown = false;
        $this->show_flat_dropdown = false;
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

                // Generate sale number
                $saleNumber = FlatSale::generateSaleNumber();

                // Create flat sale record
                FlatSale::create([
                    'sale_number' => $saleNumber,
                    'customer_id' => $customer->id,
                    'flat_id' => $flat->id,
                    'sales_agent_id' => $this->seller_id ?: null,
                    'sale_date' => now()->toDateString(),
                    'nominee_name' => $this->nominee_name ?: null,
                    'nominee_nid' => $this->nominee_nid ?: null,
                    'nominee_phone' => $this->nominee_phone ?: null,
                    'nominee_relationship' => $this->nominee_relationship === 'Other' ? $this->nominee_relationship_other : ($this->nominee_relationship ?: null),
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

            // Save attachments linked to the first selected flat
            if (!empty($this->attachments) && !empty($this->selected_flats)) {
                $firstFlatId = $this->selected_flats[0]['id'];
                $displayOrder = 0;
                
                foreach ($this->attachments as $attachment) {
                    if (isset($attachment['file']) && $attachment['file']) {
                        $file = $attachment['file'];
                        
                        // Check if file is a Livewire temporary file
                        if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                            $extension = $file->getClientOriginalExtension();
                            $fileName = time() . '_' . uniqid() . '.' . $extension;
                            $filePath = $file->storeAs('document_soft_copy/flat_sale', $fileName, 'public');
                            
                            Attachment::create([
                                'document_name' => $attachment['document_name'] ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                                'file_path' => $filePath,
                                'file_size' => $file->getSize(),
                                'display_order' => $displayOrder++,
                                'flat_id' => $firstFlatId,
                            ]);
                        }
                    }
                }
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
        $this->nominee_name = '';
        $this->nominee_nid = '';
        $this->nominee_phone = '';
        $this->nominee_relationship = '';
        $this->nominee_relationship_other = '';
        $this->seller_search = '';
        $this->seller_id = '';
        $this->seller_name = '';
        $this->project_search = '';
        $this->selected_project_id = '';
        $this->selected_project = null;
        $this->flat_search = '';
        $this->selected_flat_id = '';
        $this->selected_flat = null;
        $this->selected_flats = [];
        $this->attachments = [];
        $this->tempFiles = [];
        $this->customer_results = [];
        $this->project_results = [];
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

