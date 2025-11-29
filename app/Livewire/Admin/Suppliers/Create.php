<?php

namespace App\Livewire\Admin\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $description = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:suppliers,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'description' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Supplier name is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
    ];

    public function save()
    {
        $this->validate();

        try {
            Supplier::create([
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'address' => $this->address ?: null,
                'description' => $this->description ?: null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Supplier created successfully!'
            ]);

            return redirect()->route('admin.supplier.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error creating supplier: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.suppliers.create');
    }
}
