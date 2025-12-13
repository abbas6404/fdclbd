<div class="container-fluid" style="min-height: 600px;">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h6 class="card-title mb-0 text-primary">
                        <i class="fas fa-clipboard-list me-2"></i> BOQ (Bill of Quantities)
                    </h6>
                </div>
                @if($selected_project)
                <div class="col">
                    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3 flex-wrap">
                        <div class="d-flex align-items-center gap-1">
                            <i class="fas fa-building text-primary"></i>
                            <strong class="text-primary">{{ $selected_project['name'] }}</strong>
                        </div>
                        @if($selected_project['address'] && $selected_project['address'] !== '')
                            <span class="text-muted d-none d-md-inline">|</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <span class="text-dark" style="font-size: 0.9rem;">{{ Str::limit($selected_project['address'], 40) }}</span>
                            </div>
                        @endif
                        @if($selected_project['storey'] && $selected_project['storey'] !== 'N/A')
                            <span class="text-muted d-none d-md-inline">|</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-layer-group text-warning"></i>
                                <span class="text-dark" style="font-size: 0.9rem;">{{ $selected_project['storey'] }}</span>
                            </div>
                        @endif
                        @if($selected_project['land_area'] && $selected_project['land_area'] !== 'N/A')
                            <span class="text-muted d-none d-md-inline">|</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-ruler-combined text-success"></i>
                                <span class="text-dark" style="font-size: 0.9rem;">{{ is_numeric($selected_project['land_area']) ? number_format($selected_project['land_area'], 2) . ' sq ft' : $selected_project['land_area'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary" 
                            wire:click="printBoq"
                            title="Print BOQ">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
                @else
                <div class="col ms-auto" style="max-width: 50%;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm border-start-0" 
                               wire:model.live.debounce.300ms="project_search" 
                               placeholder="Search project..." 
                               autocomplete="new-password">
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body py-3">
            @if(!$selected_project_id)
                <!-- Project Search Results Table -->
                @if(count($project_results) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Project Name</th>
                                    <th>Address</th>
                                    <th>Facing</th>
                                    <th>Storey</th>
                                    <th>Land Area</th>
                                    <th class="text-center">BOQ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project_results as $project)
                                    <tr style="cursor: pointer;" 
                                        wire:click="selectProject({{ $project['id'] }})"
                                        class="search-item">
                                        <td>
                                            <strong class="text-primary">{{ $project['name'] }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit($project['address'], 40) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $project['facing'] }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $project['storey'] }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $project['land_area'] != 'N/A' ? number_format($project['land_area'], 2) . ' sq ft' : 'N/A' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if(isset($project['boq_count']) && $project['boq_count'] > 0)
                                                <span class="badge bg-success" title="{{ $project['boq_count'] }} BOQ record(s)">
                                                    <i class="fas fa-check-circle me-1"></i>{{ $project['boq_count'] }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary" title="No BOQ records">
                                                    <i class="fas fa-times-circle me-1"></i>0
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-building fa-3x mb-3"></i>
                        <p class="mb-0">Search for a project to manage BOQ records</p>
                    </div>
                @endif
            @endif

            @if($selected_project_id)
                <div class="row">
                    <!-- Full Width - BOQ Records Table -->
                    <div class="col-12 px-0">
                        <div class="card border" wire:click.away="hideAccountDropdown">
                            <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center gap-2">
                                <h6 class="mb-0"><i class="fas fa-list me-1"></i> BOQ Records</h6>
                                <div class="position-relative" style="width: 50%;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control form-control-sm border-start-0" 
                                               wire:model.live.debounce.300ms="item_account_search" 
                                               wire:click="showAccountDropdown"
                                               wire:focus="showAccountDropdown"
                                               placeholder="Search account..." 
                                               autocomplete="new-password">
                                    </div>
                                    @if($show_account_dropdown && count($item_account_results) > 0)
                                        <div class="list-group position-absolute mt-1 bg-white border rounded" 
                                             style="max-height: 300px; overflow-y: auto; z-index: 1050; width: 100%; box-shadow: 0 4px 6px rgba(0,0,0,0.1); top: 100%; left: 0;"
                                             wire:ignore.self>
                                            @foreach($item_account_results as $account)
                                                <button type="button" 
                                                        class="list-group-item list-group-item-action search-item border-0" 
                                                        wire:click="addBoqItem({{ $account['id'] }})"
                                                        style="cursor: pointer;">
                                                    <strong class="d-block">{{ $account['account_name'] ?? 'N/A' }}</strong>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @if(count($boq_records) > 0 || count($boq_items) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 5%;">#</th>
                                                    <th style="width: 25%;">Head of Account</th>
                                                    <th class="text-end" style="width: 12%;">Planned Qty</th>
                                                    <th class="text-end" style="width: 12%;">Used Qty</th>
                                                    <th class="text-end" style="width: 12%;">Remaining Qty</th>
                                                    <th class="text-end" style="width: 12%;">Unit Rate</th>
                                                    <th class="text-end" style="width: 12%;">Planned Amount</th>
                                                    <th class="text-end" style="width: 12%;">Used Amount</th>
                                                    <th class="text-center" style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Saved Records -->
                                                @foreach($boq_records as $index => $record)
                                                    <tr>
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td class="align-middle">
                                                            <strong>{{ $record['account_name'] }}</strong>
                                                        </td>
                                                        <td class="text-end align-middle">{{ $record['planned_quantity'] }}</td>
                                                        <td class="text-end align-middle">
                                                            <span class="{{ floatval(str_replace(',', '', $record['used_quantity'])) > floatval(str_replace(',', '', $record['planned_quantity'])) ? 'text-danger' : '' }}">
                                                                {{ $record['used_quantity'] }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end align-middle">
                                                            @php
                                                                $remaining = floatval(str_replace(',', '', $record['remaining_quantity']));
                                                            @endphp
                                                            <span class="{{ $remaining < 0 ? 'text-danger' : ($remaining == 0 ? 'text-warning' : 'text-success') }}">
                                                                {{ $record['remaining_quantity'] }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end align-middle">৳{{ $record['unit_rate'] }}</td>
                                                        <td class="text-end align-middle">৳{{ $record['planned_amount'] }}</td>
                                                        <td class="text-end align-middle">৳{{ $record['used_amount'] }}</td>
                                                        <td class="text-center align-middle">
                                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                                @if(!empty($record['change_history']) && count($record['change_history']) > 0)
                                                                    <button type="button" 
                                                                            class="btn btn-sm btn-outline-info"
                                                                            wire:click="showChangeHistory({{ $record['id'] }})"
                                                                            title="Change History">
                                                                        <i class="fas fa-history"></i>
                                                                    </button>
                                                                @endif
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-primary"
                                                                        wire:click="editBoqRecord({{ $record['id'] }})"
                                                                        title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        wire:click="deleteBoqRecord({{ $record['id'] }})"
                                                                        wire:confirm="Are you sure you want to delete this BOQ record?"
                                                                        title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                                <!-- Editable Items -->
                                                @foreach($boq_items as $index => $item)
                                                    <tr class="table-warning">
                                                        <td class="text-center align-middle">
                                                            <strong>{{ count($boq_records) + $loop->iteration }}</strong>
                                                        </td>
                                                        <td class="p-0 position-relative">
                                                            @if($item['head_of_account_id'])
                                                                <div class="px-2 py-1">
                                                                    <strong>{{ $item['account_name'] }}</strong>
                                                                </div>
                                                            @else
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" 
                                                                           class="form-control form-control-sm border-0 rounded-0" 
                                                                           wire:model.live.debounce.300ms="boq_items.{{ $index }}.account_search" 
                                                                           placeholder="Search account..." 
                                                                           autocomplete="new-password"
                                                                           style="width: 100%; height: 100%; min-height: 38px;">
                                                                </div>
                                                                @if(isset($item['account_search']) && strlen($item['account_search']) >= 2)
                                                                    <div class="list-group position-absolute mt-1 bg-white border rounded" style="max-height: 200px; overflow-y: auto; z-index: 1000; width: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                                        @foreach(App\Models\HeadOfAccount::where('status', 'active')->where('is_boq', true)->where('account_level', '4')->where('account_name', 'like', '%' . $item['account_search'] . '%')->limit(10)->get() as $account)
                                                                            <button type="button" 
                                                                                    class="list-group-item list-group-item-action list-group-item-sm border-0"
                                                                                    wire:click="selectItemAccount({{ $account->id }}, {{ $index }})"
                                                                                    style="cursor: pointer;">
                                                                                <strong>{{ $account->account_name }}</strong>
                                                                            </button>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="p-0">
                                                            <input type="number" 
                                                                   step="0.01"
                                                                   min="0"
                                                                   class="form-control form-control-sm border-0 rounded-0 text-end" 
                                                                   wire:model="boq_items.{{ $index }}.planned_quantity"
                                                                   placeholder="0.00" 
                                                                   style="width: 100%; height: 100%; min-height: 38px;">
                                                        </td>
                                                        <td class="p-0">
                                                            <input type="number" 
                                                                   step="0.01"
                                                                   min="0"
                                                                   class="form-control form-control-sm border-0 rounded-0 text-end" 
                                                                   wire:model="boq_items.{{ $index }}.used_quantity"
                                                                   placeholder="0.00" 
                                                                   style="width: 100%; height: 100%; min-height: 38px;">
                                                        </td>
                                                        <td class="text-end align-middle">
                                                            @php
                                                                $planned = floatval($item['planned_quantity'] ?? 0);
                                                                $used = floatval($item['used_quantity'] ?? 0);
                                                                $remaining = $planned - $used;
                                                            @endphp
                                                            <span class="{{ $remaining < 0 ? 'text-danger' : ($remaining == 0 ? 'text-warning' : 'text-success') }}">
                                                                {{ number_format($remaining, 2) }}
                                                            </span>
                                                        </td>
                                                        <td class="p-0">
                                                            <input type="number" 
                                                                   step="0.01"
                                                                   min="0"
                                                                   class="form-control form-control-sm border-0 rounded-0 text-end" 
                                                                   wire:model="boq_items.{{ $index }}.unit_rate"
                                                                   placeholder="0.00" 
                                                                   style="width: 100%; height: 100%; min-height: 38px;">
                                                        </td>
                                                        <td class="text-end align-middle">
                                                            @php
                                                                $plannedAmt = $planned * floatval($item['unit_rate'] ?? 0);
                                                            @endphp
                                                            ৳{{ number_format($plannedAmt, 2) }}
                                                        </td>
                                                        <td class="text-end align-middle">
                                                            @php
                                                                $usedAmt = $used * floatval($item['unit_rate'] ?? 0);
                                                            @endphp
                                                            ৳{{ number_format($usedAmt, 2) }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    wire:click="removeBoqItem({{ $index }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            @if(count($boq_records) > 0)
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="2" class="text-end">Total:</th>
                                                    <th class="text-end">
                                                        @php
                                                            $totalPlanned = collect($boq_records)->sum(function($r) { 
                                                                return floatval(str_replace(',', '', $r['planned_quantity'])); 
                                                            });
                                                            $totalPlanned += collect($boq_items)->sum(function($i) { 
                                                                return floatval($i['planned_quantity'] ?? 0); 
                                                            });
                                                        @endphp
                                                        {{ number_format($totalPlanned, 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        @php
                                                            $totalUsed = collect($boq_records)->sum(function($r) { 
                                                                return floatval(str_replace(',', '', $r['used_quantity'])); 
                                                            });
                                                            $totalUsed += collect($boq_items)->sum(function($i) { 
                                                                return floatval($i['used_quantity'] ?? 0); 
                                                            });
                                                        @endphp
                                                        {{ number_format($totalUsed, 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        @php
                                                            $totalRemaining = collect($boq_records)->sum(function($r) { 
                                                                return floatval(str_replace(',', '', $r['remaining_quantity'])); 
                                                            });
                                                            $totalRemaining += collect($boq_items)->sum(function($i) { 
                                                                return floatval($i['planned_quantity'] ?? 0) - floatval($i['used_quantity'] ?? 0); 
                                                            });
                                                        @endphp
                                                        {{ number_format($totalRemaining, 2) }}
                                                    </th>
                                                    <th class="text-end">-</th>
                                                    <th class="text-end">
                                                        @php
                                                            $totalPlannedAmount = collect($boq_records)->sum(function($r) { 
                                                                return floatval(str_replace(',', '', $r['planned_amount'])); 
                                                            });
                                                            $totalPlannedAmount += collect($boq_items)->sum(function($i) { 
                                                                return floatval($i['planned_quantity'] ?? 0) * floatval($i['unit_rate'] ?? 0); 
                                                            });
                                                        @endphp
                                                        ৳{{ number_format($totalPlannedAmount, 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        @php
                                                            $totalUsedAmount = collect($boq_records)->sum(function($r) { 
                                                                return floatval(str_replace(',', '', $r['used_amount'])); 
                                                            });
                                                            $totalUsedAmount += collect($boq_items)->sum(function($i) { 
                                                                return floatval($i['used_quantity'] ?? 0) * floatval($i['unit_rate'] ?? 0); 
                                                            });
                                                        @endphp
                                                        ৳{{ number_format($totalUsedAmount, 2) }}
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                    
                                    @if(count($boq_items) > 0)
                                        <div class="mt-3 p-3 d-flex justify-content-between align-items-center gap-2">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary" 
                                                    wire:click="addBoqItem"
                                                    title="Add Empty Row">
                                                <i class="fas fa-plus me-1"></i> Add Row
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    wire:click="saveBoqRecords"
                                                    wire:loading.attr="disabled">
                                                <i class="fas fa-save me-1"></i> 
                                                <span wire:loading.remove wire:target="saveBoqRecords">Save</span>
                                                <span wire:loading wire:target="saveBoqRecords">Saving...</span>
                                            </button>
                                        </div>
                                    @else
                                        <div class="p-3 border-top">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary w-100" 
                                                    wire:click="addBoqItem"
                                                    title="Add Empty Row">
                                                <i class="fas fa-plus me-1"></i> Add Row
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                        <p class="mb-0">No BOQ records.</p>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary mt-3" 
                                                wire:click="addBoqItem"
                                                title="Add Empty Row">
                                            <i class="fas fa-plus me-1"></i> Add Row
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
    .search-item:hover {
        background-color: #f8f9fa;
        border-left: 3px solid #28a745 !important;
    }
    .search-item {
        transition: all 0.2s ease;
    }
    </style>

    @script
    <script>
        $wire.on('print-boq', (event) => {
            const printUrl = event.url;
            if (typeof globalPrint === 'function') {
                globalPrint(printUrl, { method: 'iframe', autoPrint: true });
            } else {
                window.open(printUrl, '_blank');
            }
        });
    </script>
    @endscript

    <!-- Change History Modal -->
    @if($show_history_modal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-history me-2"></i> Change History - {{ $selected_record_name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeHistoryModal"></button>
                </div>
                <div class="modal-body">
                    @if(count($selected_record_history) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">Date & Time</th>
                                        <th style="width: 15%;">Changed By</th>
                                        <th style="width: 20%;">Field</th>
                                        <th style="width: 22.5%;">Old Value</th>
                                        <th style="width: 22.5%;">New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_reverse($selected_record_history) as $history)
                                        @foreach($history['changes'] ?? [] as $change)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($history['date'])->format('d/m/Y h:i A') }}</td>
                                                <td>{{ $history['user_name'] ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ ucfirst(str_replace('_', ' ', $change['field'])) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($change['old_value'] !== null)
                                                        @if(in_array($change['field'], ['planned_quantity', 'used_quantity', 'unit_rate']))
                                                            {{ number_format($change['old_value'], 2) }}
                                                        @else
                                                            {{ $change['old_value'] }}
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(in_array($change['field'], ['planned_quantity', 'used_quantity', 'unit_rate']))
                                                        {{ number_format($change['new_value'], 2) }}
                                                    @else
                                                        {{ $change['new_value'] }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-history fa-3x mb-3"></i>
                            <p class="mb-0">No change history available for this record.</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeHistoryModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
