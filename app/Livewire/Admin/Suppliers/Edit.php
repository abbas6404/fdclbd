<?php

namespace App\Livewire\Admin\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $supplierId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $description = '';

    public function mount($id)
    {
        $this->supplierId = $id;
        $supplier = Supplier::findOrFail($id);
        
        $this->name = $supplier->name;
        $this->email = $supplier->email ?? '';
        $this->phone = $supplier->phone ?? '';
        $this->address = $supplier->address ?? '';
        $this->description = $supplier->description ?? '';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $this->supplierId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

    protected $messages = [
        'name.required' => 'Supplier name is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
    ];

    public function update()
    {
        $this->validate();

        try {
            $supplier = Supplier::findOrFail($this->supplierId);
            
            $supplier->update([
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'address' => $this->address ?: null,
                'description' => $this->description ?: null,
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Supplier updated successfully!'
            ]);

            return redirect()->route('admin.supplier.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error updating supplier: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.suppliers.edit');
    }
}
