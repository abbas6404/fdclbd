<?php

namespace App\Livewire\Admin\SalesAgents;

use Livewire\Component;
use App\Models\SalesAgent;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name = '';
    public $phone = '';
    public $nid_or_passport_number = '';
    public $address = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20|unique:sales_agents,phone',
        'nid_or_passport_number' => 'nullable|string|max:50|unique:sales_agents,nid_or_passport_number',
        'address' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Sales agent name is required.',
        'phone.unique' => 'This phone number is already registered.',
        'nid_or_passport_number.unique' => 'This NID/Passport number is already registered.',
    ];

    public function save()
    {
        $this->validate();

        try {
            SalesAgent::create([
                'name' => $this->name,
                'phone' => $this->phone ?: null,
                'nid_or_passport_number' => $this->nid_or_passport_number ?: null,
                'address' => $this->address ?: null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Sales agent created successfully!'
            ]);

            return redirect()->route('admin.sales-agents.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error creating sales agent: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.sales-agents.create');
    }
}
