<div class="container-fluid px-2 px-md-3">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-plus-circle me-2"></i> Create New Project
                </h6>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body py-3">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <!-- Project Name -->
                    <div class="col-md-6">
                        <label for="project_name" class="form-label small fw-bold">Project Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-sm @error('project_name') is-invalid @enderror" 
                               id="project_name" 
                               wire:model="project_name" 
                               placeholder="Enter project name">
                        @error('project_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label small fw-bold">Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm @error('status') is-invalid @enderror" 
                                id="status" 
                                wire:model="status">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address and Description in same row -->
                    <div class="col-md-6">
                        <label for="address" class="form-label small fw-bold">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm @error('address') is-invalid @enderror" 
                                  id="address" 
                                  wire:model="address" 
                                  rows="3" 
                                  placeholder="Enter complete address"></textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="description" class="form-label small fw-bold">Description</label>
                        <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                  id="description" 
                                  wire:model="description" 
                                  rows="3" 
                                  placeholder="Enter project description"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Facing -->
                    <div class="col-md-3">
                        <label for="facing" class="form-label small fw-bold">Facing Direction</label>
                        <select class="form-select form-select-sm @error('facing') is-invalid @enderror" 
                                id="facing" 
                                wire:model="facing">
                            <option value="">Select Facing</option>
                            <option value="North">North</option>
                            <option value="South">South</option>
                            <option value="East">East</option>
                            <option value="West">West</option>
                            <option value="North-East">North-East</option>
                            <option value="North-West">North-West</option>
                            <option value="South-East">South-East</option>
                            <option value="South-West">South-West</option>
                        </select>
                        @error('facing')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Storey -->
                    <div class="col-md-3">
                        <label for="storey" class="form-label small fw-bold">Storey</label>
                        <input type="number" 
                               class="form-control form-control-sm @error('storey') is-invalid @enderror" 
                               id="storey" 
                               wire:model="storey" 
                               min="1"
                               placeholder="Enter storey">
                        @error('storey')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Land Area -->
                    <div class="col-md-3">
                        <label for="land_area" class="form-label small fw-bold">Land Area (sq ft)</label>
                        <input type="number" 
                               class="form-control form-control-sm @error('land_area') is-invalid @enderror" 
                               id="land_area" 
                               wire:model="land_area" 
                               step="0.01"
                               min="0"
                               placeholder="Enter land area">
                        @error('land_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Total Floors -->
                    <div class="col-md-3">
                        <label for="total_floors" class="form-label small fw-bold">Total Floors</label>
                        <input type="number" 
                               class="form-control form-control-sm @error('total_floors') is-invalid @enderror" 
                               id="total_floors" 
                               wire:model="total_floors" 
                               min="1" 
                               placeholder="Enter total floors">
                        @error('total_floors')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Project Launching Date -->
                    <div class="col-md-6">
                        <label for="project_launching_date" class="form-label small fw-bold">Project Launching Date</label>
                        <input type="date" 
                               class="form-control form-control-sm @error('project_launching_date') is-invalid @enderror" 
                               id="project_launching_date" 
                               wire:model="project_launching_date">
                        @error('project_launching_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Project Hand Over Date -->
                    <div class="col-md-6">
                        <label for="project_hand_over_date" class="form-label small fw-bold">Project Hand Over Date</label>
                        <input type="date" 
                               class="form-control form-control-sm @error('project_hand_over_date') is-invalid @enderror" 
                               id="project_hand_over_date" 
                               wire:model="project_hand_over_date">
                        @error('project_hand_over_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Land Owner Information and Attachments Section -->
                    <div class="col-12 mt-3">
                        <div class="row">
                            <!-- Left Side: Land Owner Information -->
                            <div class="col-md-4">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-tie me-2"></i>Land Owner Information
                                </h6>
                                
                                <!-- Land Owner Name -->
                                <div class="mb-3">
                                    <label for="land_owner_name" class="form-label small fw-bold">Land Owner Name</label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('land_owner_name') is-invalid @enderror" 
                                           id="land_owner_name" 
                                           wire:model="land_owner_name" 
                                           placeholder="Enter land owner name">
                                    @error('land_owner_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Land Owner NID -->
                                <div class="mb-3">
                                    <label for="land_owner_nid" class="form-label small fw-bold">Land Owner NID</label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('land_owner_nid') is-invalid @enderror" 
                                           id="land_owner_nid" 
                                           wire:model="land_owner_nid" 
                                           placeholder="Enter NID number">
                                    @error('land_owner_nid')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Land Owner Phone -->
                                <div class="mb-3">
                                    <label for="land_owner_phone" class="form-label small fw-bold">Land Owner Phone</label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('land_owner_phone') is-invalid @enderror" 
                                           id="land_owner_phone" 
                                           wire:model="land_owner_phone" 
                                           placeholder="Enter phone number">
                                    @error('land_owner_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Side: Attachments -->
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-primary border-bottom pb-2 mb-0">
                                        <i class="fas fa-paperclip me-2"></i>Document Soft Copy
                                    </h6>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary" 
                                            wire:click="addAttachment">
                                        <i class="fas fa-plus me-1"></i> Add File
                                    </button>
                                </div>

                                <!-- Drag and Drop Zone -->
                                <div class="border border-dashed border-primary rounded p-3 mb-3 text-center" 
                                     style="background-color: #f8f9fa; cursor: pointer; transition: background-color 0.3s;"
                                     onclick="document.getElementById('file-drop-zone-input').click()"
                                     ondrop="handleDrop(event)" 
                                     ondragover="handleDragOver(event)" 
                                     ondragleave="handleDragLeave(event)"
                                     id="drop-zone">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                    <p class="mb-0 small text-muted">
                                        Drag and drop images or PDF files here or <span class="text-primary">click to browse</span>
                                    </p>
                                    <p class="mb-0 small text-muted" style="font-size: 0.7rem;">
                                        Accepted formats: JPG, PNG, GIF, WEBP, PDF (Max 10MB)
                                    </p>
                                    <input type="file" 
                                           id="file-drop-zone-input" 
                                           class="d-none" 
                                           multiple
                                           accept="image/*,.pdf"
                                           wire:model.live="tempFiles">
                                </div>

                                @if(!empty($attachments))
                                    @foreach($attachments as $index => $attachment)
                                        <div class="card mb-2 border">
                                            <div class="card-body p-2">
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-auto text-center" style="width: 30px;">
                                                        <span class="text-muted">{{ $loop->iteration }}.</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label small fw-bold">Document Name</label>
                                                        <input type="text" 
                                                               class="form-control form-control-sm @error('attachments.'.$index.'.document_name') is-invalid @enderror" 
                                                               wire:model="attachments.{{ $index }}.document_name" 
                                                               placeholder="Enter document name">
                                                        @error('attachments.'.$index.'.document_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label small fw-bold">File (Image/PDF)</label>
                                                        <input type="file" 
                                                               class="form-control form-control-sm @error('attachments.'.$index.'.file') is-invalid @enderror" 
                                                               accept="image/*,.pdf"
                                                               wire:model="attachments.{{ $index }}.file">
                                                        @error('attachments.'.$index.'.file')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        @if(isset($attachment['file']) && $attachment['file'])
                                                            <small class="text-muted d-block mt-1">
                                                                <i class="fas fa-file me-1"></i>
                                                                {{ is_string($attachment['file']) ? $attachment['file'] : $attachment['file']->getClientOriginalName() }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button" 
                                                                class="btn btn-xs btn-outline-danger" 
                                                                wire:click="removeAttachment({{ $index }})"
                                                                title="Remove">
                                                            <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function handleDragOver(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.currentTarget.style.backgroundColor = '#e3f2fd';
                        e.currentTarget.style.borderColor = '#2196F3';
                    }

                    function handleDragLeave(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.currentTarget.style.backgroundColor = '#f8f9fa';
                        e.currentTarget.style.borderColor = '#0d6efd';
                    }

                    function handleDrop(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.currentTarget.style.backgroundColor = '#f8f9fa';
                        e.currentTarget.style.borderColor = '#0d6efd';
                        
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            const fileInput = document.getElementById('file-drop-zone-input');
                            const dataTransfer = new DataTransfer();
                            
                            for (let i = 0; i < files.length; i++) {
                                dataTransfer.items.add(files[i]);
                            }
                            
                            fileInput.files = dataTransfer.files;
                            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }

                </script>

                <!-- Form Actions -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-secondary" 
                                    wire:click="cancel">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            <button type="submit" 
                                    class="btn btn-sm btn-primary" 
                                    wire:loading.attr="disabled"
                                    wire:target="save">
                                <span wire:loading.remove wire:target="save">
                                    <i class="fas fa-save me-1"></i> Create Project
                                </span>
                                <span wire:loading wire:target="save">
                                    <i class="fas fa-spinner fa-spin me-1"></i> Creating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>