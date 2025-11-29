<?php

namespace App\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $customerId;
    public $name = '';
    public $father_or_husband_name = '';
    public $phone = '';
    public $email = '';
    public $nid_or_passport_number = '';
    public $address = '';

    public function mount($id)
    {
        $this->customerId = $id;
        $customer = Customer::findOrFail($id);
        
        $this->name = $customer->name;
        $this->father_or_husband_name = $customer->father_or_husband_name ?? '';
        $this->phone = $customer->phone ?? '';
        $this->email = $customer->email ?? '';
        $this->nid_or_passport_number = $customer->nid_or_passport_number ?? '';
        $this->address = $customer->address ?? '';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'father_or_husband_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone,' . $this->customerId,
            'email' => 'nullable|email|max:255|unique:customers,email,' . $this->customerId,
            'nid_or_passport_number' => 'nullable|string|max:50|unique:customers,nid_or_passport_number,' . $this->customerId,
            'address' => 'nullable|string',
        ];
    }

    protected $messages = [
        'name.required' => 'Customer name is required.',
        'phone.unique' => 'This phone number is already registered.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'nid_or_passport_number.unique' => 'This NID/Passport number is already registered.',
    ];

    public function update()
    {
        $this->validate();

        try {
            $customer = Customer::findOrFail($this->customerId);
            
            $customer->update([
                'name' => $this->name,
                'father_or_husband_name' => $this->father_or_husband_name ?: null,
                'phone' => $this->phone ?: null,
                'email' => $this->email ?: null,
                'nid_or_passport_number' => $this->nid_or_passport_number ?: null,
                'address' => $this->address ?: null,
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Customer updated successfully!'
            ]);

            return redirect()->route('admin.customers.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error updating customer: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.customers.edit');
    }
}
