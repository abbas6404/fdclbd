<?php

namespace App\Livewire\Admin\SalesAgents;

use Livewire\Component;
use App\Models\SalesAgent;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $salesAgentId;
    public $name = '';
    public $phone = '';
    public $nid_or_passport_number = '';
    public $address = '';

    public function mount($id)
    {
        $this->salesAgentId = $id;
        $salesAgent = SalesAgent::findOrFail($id);
        
        $this->name = $salesAgent->name;
        $this->phone = $salesAgent->phone ?? '';
        $this->nid_or_passport_number = $salesAgent->nid_or_passport_number ?? '';
        $this->address = $salesAgent->address ?? '';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:sales_agents,phone,' . $this->salesAgentId,
            'nid_or_passport_number' => 'nullable|string|max:50|unique:sales_agents,nid_or_passport_number,' . $this->salesAgentId,
            'address' => 'nullable|string',
        ];
    }

    protected $messages = [
        'name.required' => 'Sales agent name is required.',
        'phone.unique' => 'This phone number is already registered.',
        'nid_or_passport_number.unique' => 'This NID/Passport number is already registered.',
    ];

    public function update()
    {
        $this->validate();

        try {
            $salesAgent = SalesAgent::findOrFail($this->salesAgentId);
            
            $salesAgent->update([
                'name' => $this->name,
                'phone' => $this->phone ?: null,
                'nid_or_passport_number' => $this->nid_or_passport_number ?: null,
                'address' => $this->address ?: null,
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Sales agent updated successfully!'
            ]);

            return redirect()->route('admin.sales-agents.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error updating sales agent: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.sales-agents.edit');
    }
}
