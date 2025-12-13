<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-layer-group me-2"></i>Approval Levels Management
            </h5>
            <small class="text-muted">Manage approval sequence and assign users to each level</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm" wire:click="openLevelForm()">
            <i class="fas fa-plus me-1"></i>Add Approval Level
        </button>
    </div>

    <!-- Approval Levels List -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">Sequence</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 120px;">Users</th>
                    <th style="width: 200px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $level)
                <tr>
                    <td class="text-center">
                        <span class="badge bg-primary">{{ $level->sequence }}</span>
                    </td>
                    <td>
                        <strong>{{ $level->name }}</strong>
                    </td>
                    <td>
                        <code class="small">{{ $level->slug }}</code>
                    </td>
                    <td>
                        <small class="text-muted">{{ $level->description ?? '-' }}</small>
                    </td>
                    <td>
                        @if($level->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info">{{ $level->users()->count() }}</span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" 
                                    class="btn btn-outline-primary" 
                                    wire:click="openUserAssignmentModal({{ $level->id }})"
                                    title="Manage Users">
                                <i class="fas fa-users"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    wire:click="openLevelForm({{ $level->id }})"
                                    title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-outline-{{ $level->is_active ? 'warning' : 'success' }}" 
                                    wire:click="toggleLevelStatus({{ $level->id }})"
                                    title="{{ $level->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $level->is_active ? 'pause' : 'play' }}"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    wire:click="deleteLevel({{ $level->id }})"
                                    wire:confirm="Are you sure you want to delete this approval level?"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                        <p class="text-muted mb-0">No approval levels found. Click "Add Approval Level" to create one.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Approval Level Form Modal -->
    @if($showLevelForm)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-{{ $editingLevelId ? 'edit' : 'plus' }} me-2"></i>
                        {{ $editingLevelId ? 'Edit' : 'Add' }} Approval Level
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeLevelForm"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveLevel">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('levelForm.name') is-invalid @enderror" 
                                   wire:model="levelForm.name"
                                   placeholder="e.g., Fast, Director, Chairman">
                            @error('levelForm.name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" 
                                   class="form-control @error('levelForm.slug') is-invalid @enderror" 
                                   wire:model="levelForm.slug"
                                   placeholder="auto-generated from name">
                            @error('levelForm.slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">URL-friendly identifier (auto-generated)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sequence <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('levelForm.sequence') is-invalid @enderror" 
                                   wire:model="levelForm.sequence"
                                   min="1"
                                   placeholder="1, 2, 3...">
                            @error('levelForm.sequence')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Order in approval workflow (1 = first, 2 = second, etc.)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('levelForm.description') is-invalid @enderror" 
                                      wire:model="levelForm.description"
                                      rows="3"
                                      placeholder="Optional description"></textarea>
                            @error('levelForm.description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       wire:model="levelForm.is_active"
                                       id="is_active">
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" wire:click="closeLevelForm">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- User Assignment Modal -->
    @if($showUserAssignmentModal && $selectedLevelId)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-users me-2"></i>
                        Manage Users - {{ $levels->firstWhere('id', $selectedLevelId)?->name ?? 'Unknown' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeUserAssignmentModal"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Users -->
                    <div class="mb-4">
                        <label class="form-label">Search Users to Assign</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   wire:model.live.debounce.300ms="userSearch"
                                   placeholder="Type name or email to search...">
                        </div>
                        
                        @if(count($userResults) > 0)
                        <div class="mt-2">
                            <small class="text-muted">Search Results:</small>
                            <div class="list-group mt-2">
                                @foreach($userResults as $user)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-sm btn-primary"
                                            wire:click="assignUser({{ $user->id }})">
                                        <i class="fas fa-plus me-1"></i>Assign
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @elseif(strlen($userSearch) >= 2)
                        <div class="mt-2">
                            <small class="text-muted">No users found matching your search.</small>
                        </div>
                        @endif
                    </div>

                    <!-- Assigned Users -->
                    <div>
                        <label class="form-label">Assigned Users ({{ count($assignedUsers) }})</label>
                        @if(count($assignedUsers) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th style="width: 100px;" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    wire:click="removeUser({{ $user->id }})"
                                                    wire:confirm="Are you sure you want to remove this user from this approval level?">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            No users assigned to this approval level. Use the search above to assign users.
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeUserAssignmentModal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
