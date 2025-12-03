<div class="container-fluid" wire:click.self="closeAllDropdowns">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-shopping-cart me-2"></i> Flat Sales
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Flat Sales Form -->
            <div class="row mb-4" style="display: flex; flex-wrap: nowrap;">
                <!-- ============================================ -->
                <!-- LEFT COLUMN - CUSTOMER DETAILS -->
                <!-- ============================================ -->
                <div class="col-md-6 pe-2" style="overflow: visible; min-width: 0;">
                    <!-- Customer Details Card -->
                    <div class="card border" style="overflow: visible;">
                        <div class="card-header bg-light py-1">
                            <h6 class="mb-0"><i class="fas fa-user me-1"></i> Customer Details</h6>
                        </div>
                        <div class="card-body position-relative" style="overflow: visible;">
                            <div class="row mb-0">
                                <div class="col-md-12">
                                    <div class="row  position-relative">
                                        <label class="col-sm-3 col-form-label">S. Customer</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="customer-search" class="form-control form-control-sm" 
                                                   wire:model.live.debounce.300ms="customer_search" 
                                                   wire:click="showRecentCustomers"
                                                    wire:focus="showRecentCustomers"
                                                    onblur="setTimeout(() => @this.set('show_customer_dropdown', false), 200)"
                                                   placeholder="Search by name, phone, email, or NID..." 
                                                   autocomplete="off"
                                                   autocapitalize="off"
                                                   autocorrect="off"
                                                   spellcheck="false"
                                                   data-lpignore="true"
                                                   data-form-type="other">
                                            @if($show_customer_dropdown && $active_search_type === 'customer')
                                            <div class="position-absolute bg-white border rounded shadow-lg mt-1" style="z-index: 1000; max-height: 300px; overflow-y: auto; width: max-content; min-width: 100%; max-width: 600px; left: 0; right: 0;">
                                                @if(count($customer_results) > 0)
                                                <table class="table table-sm table-hover mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th class="small">Name</th>
                                                            <th class="small">Phone</th>
                                                            <th class="small">Email</th>
                                                            <th class="small">NID</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($customer_results as $result)
                                                        <tr class="search-item" 
                                                            wire:click="selectCustomer({{ $result['id'] }})"
                                                            style="cursor: pointer;">
                                                            <td class="small text-nowrap arrow-indicator" title="{{ $result['name'] ?? 'N/A' }}">
                                                                <span class="arrow-icon">▶</span>
                                                                <strong>{{ $result['name'] ?? 'N/A' }}</strong>
                                                            </td>
                                                            <td class="small text-nowrap" title="{{ $result['phone'] ?? 'N/A' }}">
                                                                {{ $result['phone'] ?? 'N/A' }}
                                                            </td>
                                                            <td class="small text-nowrap" title="{{ $result['email'] ?? 'N/A' }}">
                                                                {{ Str::limit($result['email'] ?? 'N/A', 25) }}
                                                            </td>
                                                            <td class="small text-nowrap" title="{{ $result['nid_or_passport_number'] ?? 'N/A' }}">
                                                                {{ Str::limit($result['nid_or_passport_number'] ?? 'N/A', 15) }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @else
                                                <div class="p-3 text-center text-muted">
                                                    <i class="fas fa-search fa-2x mb-2"></i>
                                                    <p class="mb-0">No customers found</p>
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                        <label class="col-sm-3 col-form-label">Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input id="customer-name" placeholder="Customer name" type="text" 
                                                   class="form-control form-control-sm @error('customer_name') is-invalid @enderror" 
                                                   wire:model="customer_name" autocomplete="new-password">
                                            @error('customer_name') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Phone<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input id="customer-phone" placeholder="Phone number" type="text" 
                                                   class="form-control form-control-sm @error('customer_phone') is-invalid @enderror" 
                                                   wire:model="customer_phone" autocomplete="new-password">
                                            @error('customer_phone') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input id="customer-email" placeholder="Email address" type="email" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="customer_email" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">NID or Pass<span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input id="customer-nid" placeholder="NID or Passport number" type="text" 
                                                   class="form-control form-control-sm @error('customer_nid') is-invalid @enderror" 
                                                   wire:model="customer_nid" autocomplete="new-password">
                                            @error('customer_nid') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Address</label>
                                        <div class="col-sm-9">
                                            <textarea id="customer-address" placeholder="Customer address..." 
                                                      class="form-control form-control-sm" rows="1" 
                                                      wire:model="customer_address" autocomplete="new-password"></textarea>
                                        </div>
                                    </div>

                                    <!-- Nominee Information -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="mb-2 text-primary">
                                                <i class="fas fa-user-tie me-1"></i> Nominee Information
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Nominee Name</label>
                                        <div class="col-sm-9">
                                            <input id="nominee-name" placeholder="Nominee name" type="text" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="nominee_name" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Nominee NID/Pass</label>
                                        <div class="col-sm-9">
                                            <input id="nominee-nid" placeholder="Nominee NID or Passport number" type="text" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="nominee_nid" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Nominee Phone</label>
                                        <div class="col-sm-9">
                                            <input id="nominee-phone" placeholder="Nominee phone number" type="text" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="nominee_phone" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Relationship</label>
                                        <div class="col-sm-9">
                                            <select id="nominee-relationship" class="form-control form-control-sm" 
                                                    wire:model="nominee_relationship">
                                                <option value="">Select relationship</option>
                                                <option value="Spouse">Spouse</option>
                                                <option value="Son">Son</option>
                                                <option value="Daughter">Daughter</option>
                                                <option value="Father">Father</option>
                                                <option value="Mother">Mother</option>
                                                <option value="Brother">Brother</option>
                                                <option value="Sister">Sister</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if($nominee_relationship === 'Other')
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Specify Relationship</label>
                                        <div class="col-sm-9">
                                            <input id="nominee-relationship-other" placeholder="Enter relationship" type="text" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="nominee_relationship_other" autocomplete="new-password">
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Document Soft Copy -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label mb-0 fw-bold text-primary">
                                                    <i class="fas fa-paperclip me-1"></i> Document Soft Copy
                                                </label>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary" 
                                                        wire:click="addAttachment">
                                                    <i class="fas fa-plus me-1"></i> Add File
                                                </button>
                                            </div>
                                            
                                            @if(!empty($attachments))
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th style="width: 50px;" class="text-center">#</th>
                                                                <th>Document Name</th>
                                                                <th>File</th>
                                                                <th style="width: 60px;" class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($attachments as $index => $attachment)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <span class="text-muted">{{ $loop->iteration }}</span>
                                                                </td>
                                                                <td>
                                                                    <input type="text" 
                                                                           class="form-control form-control-sm @error('attachments.'.$index.'.document_name') is-invalid @enderror" 
                                                                           wire:model.blur="attachments.{{ $index }}.document_name" 
                                                                           placeholder="Enter document name">
                                                                    @error('attachments.'.$index.'.document_name')
                                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="file" 
                                                                           class="form-control form-control-sm @error('attachments.'.$index.'.file') is-invalid @enderror" 
                                                                           accept="image/*,.pdf"
                                                                           wire:model="attachments.{{ $index }}.file">
                                                                    @error('attachments.'.$index.'.file')
                                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                    @enderror
                                                                    @if(isset($attachment['file']) && $attachment['file'])
                                                                        <small class="text-muted d-block mt-1">
                                                                            <i class="fas fa-file me-1"></i>
                                                                            {{ is_string($attachment['file']) ? $attachment['file'] : $attachment['file']->getClientOriginalName() }}
                                                                        </small>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" 
                                                                            class="btn btn-xs btn-outline-danger" 
                                                                            wire:click="removeAttachment({{ $index }})"
                                                                            title="Remove">
                                                                        <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================ -->
                <!-- RIGHT COLUMN - FLAT INFORMATION -->
                <!-- ============================================ -->
                <div class="col-md-6 ps-2" style="overflow: visible; min-width: 0;">
                    <!-- Flat Information Card -->
                    <div class="card border">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0"><i class="fas fa-home me-1"></i> Flat Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                     <!-- 1. Project Search -->
                                     <div class="row position-relative">
                                         <label class="col-sm-3 col-form-label">Project</label>
                                         <div class="col-sm-9">
                                             <div class="input-group">
                                                 <input type="text" id="project-search" class="form-control form-control-sm" 
                                                        wire:model.live.debounce.300ms="project_search" 
                                                        wire:click="showRecentProjects"
                                                        wire:focus="showRecentProjects"
                                                        onblur="setTimeout(() => @this.set('show_project_dropdown', false), 200)"
                                                        placeholder="Search project..." 
                                                        value="{{ $selected_project ? $selected_project['project_name'] : '' }}"
                                                        autocomplete="off"
                                                        autocapitalize="off"
                                                        autocorrect="off"
                                                        spellcheck="false"
                                                        data-lpignore="true"
                                                        data-form-type="other">
                                                 @if($selected_project)
                                                 <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearProject" title="Clear Project">
                                                     <i class="fas fa-times"></i>
                                                 </button>
                                                 @endif
                                             </div>
                                             @if($show_project_dropdown && $active_search_type === 'project')
                                             <div class="position-absolute bg-white border rounded shadow-lg mt-1" style="z-index: 1000; max-height: 300px; overflow-y: auto; width: max-content; min-width: 100%; max-width: 600px; left: 0; right: 0;">
                                                 @if(count($project_results) > 0)
                                                 <table class="table table-sm table-hover mb-0">
                                                     <thead class="table-light sticky-top">
                                                         <tr>
                                                             <th class="small">Project Name</th>
                                                             <th class="small">Address</th>
                                                         </tr>
                                                     </thead>
                                                     <tbody>
                                                         @foreach($project_results as $result)
                                                         <tr class="search-item" 
                                                             wire:click="selectProject({{ $result['id'] }})"
                                                             style="cursor: pointer;">
                                                             <td class="small text-nowrap arrow-indicator" title="{{ $result['project_name'] ?? 'N/A' }}">
                                                                 <span class="arrow-icon">▶</span>
                                                                 <strong>{{ $result['project_name'] ?? 'N/A' }}</strong>
                                                             </td>
                                                             <td class="small text-nowrap" title="{{ $result['address'] ?? 'N/A' }}">
                                                                 {{ Str::limit($result['address'] ?? 'N/A', 30) }}
                                                             </td>
                                                         </tr>
                                                         @endforeach
                                                     </tbody>
                                                 </table>
                                                 @else
                                                 <div class="p-3 text-center text-muted">
                                                     <i class="fas fa-search fa-2x mb-2"></i>
                                                     <p class="mb-0">No projects found</p>
                                                 </div>
                                                 @endif
                                             </div>
                                             @endif
                                         </div>
                                     </div>

                                     <!-- 2. Address -->
                                     <div class="row">
                                         <label class="col-sm-3 col-form-label">Address</label>
                                         <div class="col-sm-9">
                                             <div class="form-control form-control-sm bg-light">
                                                 @if($selected_project && $selected_project['address'])
                                                     <i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $selected_project['address'] }}
                                                 @else
                                                     <span class="text-muted">N/A</span>
                                                 @endif
                                             </div>
                                         </div>
                                     </div>

                                     <!-- 3. Flat Search -->
                                     <div class="row position-relative">
                                         <label class="col-sm-3 col-form-label">Flat Search</label>
                                         <div class="col-sm-9">
                                             <div class="input-group">
                                                 <input type="text" id="flat-search" class="form-control form-control-sm" 
                                                        wire:model.live.debounce.300ms="flat_search" 
                                                        wire:click="showRecentFlats"
                                                        wire:focus="showRecentFlats"
                                                        onblur="setTimeout(() => @this.set('show_flat_dropdown', false), 200)"
                                                        placeholder="Search flat number, type, floor..." 
                                                        value="{{ $selected_flat && isset($selected_flat['flat_number']) ? $selected_flat['flat_number'] : '' }}"
                                                        autocomplete="off"
                                                        autocapitalize="off"
                                                        autocorrect="off"
                                                        spellcheck="false"
                                                        data-lpignore="true"
                                                        data-form-type="other">
                                                 @if($selected_flat && isset($selected_flat['id']))
                                                 <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearSelectedFlat" title="Clear Flat">
                                                     <i class="fas fa-times"></i>
                                                 </button>
                                                 @endif
                                             </div>
                                             @if($show_flat_dropdown && $active_search_type === 'flat')
                                             <div class="position-absolute bg-white border rounded shadow-lg mt-1" style="z-index: 1000; max-height: 300px; overflow-y: auto; width: max-content; min-width: 100%; max-width: 700px; left: 0; right: 0;">
                                                 @if(count($flat_results) > 0)
                                                 <table class="table table-sm table-hover mb-0">
                                                     <thead class="table-light sticky-top">
                                                         <tr>
                                                             <th class="small">Flat Number</th>
                                                             <th class="small">Type</th>
                                                             <th class="small">Floor</th>
                                                             <th class="small">Size</th>
                                                             <th class="small">Project</th>
                                                         </tr>
                                                     </thead>
                                                     <tbody>
                                                         @foreach($flat_results as $result)
                                                         <tr class="search-item" 
                                                             wire:click="selectFlat({{ $result['id'] }})"
                                                             style="cursor: pointer;">
                                                             <td class="small text-nowrap arrow-indicator" title="{{ $result['flat_number'] ?? 'N/A' }}">
                                                                 <span class="arrow-icon">▶</span>
                                                                 <strong>{{ $result['flat_number'] ?? 'N/A' }}</strong>
                                                             </td>
                                                             <td class="small text-nowrap">
                                                                 {{ $result['flat_type'] ?? 'N/A' }}
                                                             </td>
                                                             <td class="small text-nowrap">
                                                                 {{ $result['floor_number'] ?? 'N/A' }}
                                                             </td>
                                                             <td class="small text-nowrap">
                                                                 {{ $result['flat_size'] ? number_format($result['flat_size'], 2) . ' sq ft' : 'N/A' }}
                                                             </td>
                                                             <td class="small text-nowrap" title="{{ $result['project_name'] ?? 'N/A' }}">
                                                                 {{ Str::limit($result['project_name'] ?? 'N/A', 20) }}
                                                             </td>
                                                         </tr>
                                                         @endforeach
                                                     </tbody>
                                                 </table>
                                                 @else
                                                 <div class="p-3 text-center text-muted">
                                                     <i class="fas fa-search fa-2x mb-2"></i>
                                                     <p class="mb-0">No flats found</p>
                                                 </div>
                                                 @endif
                                             </div>
                                             @endif
                                         </div>
                                     </div>

                                     <!-- 4. Flat Type -->
                                     <div class="row">
                                         <label class="col-sm-3 col-form-label">Flat Type</label>
                                         <div class="col-sm-9">
                                             <div class="form-control form-control-sm bg-light">
                                                 {{ $selected_flat && isset($selected_flat['flat_type']) ? $selected_flat['flat_type'] : 'N/A' }}
                                             </div>
                                         </div>
                                     </div>

                                     <!-- 5. Floor -->
                                     <div class="row">
                                         <label class="col-sm-3 col-form-label">Floor</label>
                                         <div class="col-sm-9">
                                             <div class="form-control form-control-sm bg-light">
                                                 {{ $selected_flat && isset($selected_flat['floor_number']) ? $selected_flat['floor_number'] : 'N/A' }}
                                             </div>
                                         </div>
                                     </div>

                                     <!-- 6. Size -->
                                     <div class="row">
                                         <label class="col-sm-3 col-form-label">Size</label>
                                         <div class="col-sm-9">
                                             <div class="form-control form-control-sm bg-light">
                                                 @if($selected_flat && isset($selected_flat['flat_size']) && $selected_flat['flat_size'])
                                                     {{ number_format($selected_flat['flat_size'], 2) . ' sq ft' }}
                                                 @else
                                                     N/A
                                                 @endif
                                             </div>
                                         </div>
                                     </div>

                                    <!-- 7. Sales Agent -->
                                    <div class="row position-relative">
                                        <label class="col-sm-3 col-form-label">Sales Agent</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" id="seller-search" class="form-control form-control-sm" 
                                                       wire:model.live.debounce.300ms="seller_search" 
                                                       wire:click="showRecentAgents"
                                                       wire:focus="showRecentAgents"
                                                       onblur="setTimeout(() => @this.set('show_seller_dropdown', false), 200)"
                                                       placeholder="Search seller by name or phone..." 
                                                       value="{{ $seller_id ? $seller_name : '' }}"
                                                       autocomplete="off"
                                                       autocapitalize="off"
                                                       autocorrect="off"
                                                       spellcheck="false"
                                                       data-lpignore="true"
                                                       data-form-type="other">
                                                @if($seller_id)
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearSeller" title="Clear Sales Agent">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                            @if($show_seller_dropdown && $active_search_type === 'seller')
                                            <div class="position-absolute bg-white border rounded shadow-lg mt-1" style="z-index: 1000; max-height: 300px; overflow-y: auto; width: max-content; min-width: 100%; max-width: 600px; left: 0; right: 0;">
                                                 @if(count($seller_results) > 0)
                                                 <table class="table table-sm table-hover mb-0">
                                                     <thead class="table-light sticky-top">
                                                         <tr>
                                                             <th class="small">Name</th>
                                                             <th class="small">Phone</th>
                                                             <th class="small">NID</th>
                                                         </tr>
                                                     </thead>
                                                     <tbody>
                                                         @foreach($seller_results as $result)
                                                         <tr class="search-item" 
                                                             wire:click="selectSeller({{ $result['id'] }})"
                                                             style="cursor: pointer;">
                                                             <td class="small text-nowrap arrow-indicator" title="{{ $result['name'] ?? 'N/A' }}">
                                                                 <span class="arrow-icon">▶</span>
                                                                 <strong>{{ $result['name'] ?? 'N/A' }}</strong>
                                                             </td>
                                                             <td class="small text-nowrap">
                                                                 {{ $result['phone'] ?? 'N/A' }}
                                                             </td>
                                                             <td class="small text-nowrap" title="{{ $result['nid_or_passport_number'] ?? 'N/A' }}">
                                                                 {{ Str::limit($result['nid_or_passport_number'] ?? 'N/A', 15) }}
                                                             </td>
                                                         </tr>
                                                         @endforeach
                                                     </tbody>
                                                 </table>
                                                 @else
                                                 <div class="p-3 text-center text-muted">
                                                     <i class="fas fa-search fa-2x mb-2"></i>
                                                     <p class="mb-0">No sales agents found</p>
                                                 </div>
                                                 @endif
                                             </div>
                                             @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Agent Phone -->
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">Agent Phone</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm bg-light" 
                                                   value="{{ $seller_phone ?? 'N/A' }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Agent NID -->
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">Agent NID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm bg-light" 
                                                   value="{{ $seller_nid ?? 'N/A' }}" readonly>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @error('selected_flats') 
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @enderror
            
            <div class="d-flex justify-content-center gap-2 mt-4 mb-3 pt-4 border-top">
                <button class="btn btn-primary" wire:click="saveSale" 
                        wire:loading.attr="disabled">
                    <i class="fas fa-save me-1"></i> 
                    <span wire:loading.remove>Save</span>
                    <span wire:loading>Saving...</span>
                </button>
                <button class="btn btn-warning" wire:click="resetForm">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
            </div>
        </div>
    </div>
    
@push('styles')
    <style>
.row.mb-4 {
    display: flex !important;
    flex-wrap: nowrap !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}
.row.mb-4 > .col-md-6 {
    flex: 0 0 50% !important;
    max-width: 50% !important;
    width: 50% !important;
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
    box-sizing: border-box !important;
}
.row.mb-4 > .col-md-6:first-child {
    padding-left: 0 !important;
    padding-right: 0.5rem !important;
}
.row.mb-4 > .col-md-6:last-child {
    padding-left: 0.5rem !important;
    padding-right: 0 !important;
}
@media (max-width: 991.98px) {
    .row.mb-4 {
        flex-wrap: wrap !important;
    }
    .row.mb-4 > .col-md-6 {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .row.mb-4 > .col-md-6:first-child {
        padding-right: 0 !important;
    }
    .row.mb-4 > .col-md-6:last-child {
        padding-left: 0 !important;
        margin-top: 1rem;
    }
}
    .search-item:hover {
        background-color: #f8f9fa;
    }
    .arrow-indicator {
        position: relative;
        padding-left: 1.0rem !important;
    }
    .arrow-icon {
        position: absolute;
        left: 0rem;
        color: transparent;
        font-size: 14px;
        transition: color 0.2s ease;
        line-height: 1.8;
    }
    .search-item:hover .arrow-icon {
        color: #28a745;
    }
    .table th.small,
    .table td.small {
        font-size: 1.0rem;
        padding: 0.3rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        border-left: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
    }
    .table th.small:first-child,
    .table td.small:first-child {
        border-left: none;
    }
    .table th.small:last-child,
    .table td.small:last-child {
        border-right: none;
    }
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    </style>
@endpush
