<?php

namespace App\Livewire\Admin\Customers;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name = '';
    public $father_or_husband_name = '';
    public $phone = '';
    public $email = '';
    public $nid_or_passport_number = '';
    public $address = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'father_or_husband_name' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20|unique:customers,phone',
        'email' => 'nullable|email|max:255|unique:customers,email',
        'nid_or_passport_number' => 'nullable|string|max:50|unique:customers,nid_or_passport_number',
        'address' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Customer name is required.',
        'phone.unique' => 'This phone number is already registered.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'nid_or_passport_number.unique' => 'This NID/Passport number is already registered.',
    ];

    public function save()
    {
        $this->validate();

        try {
            Customer::create([
                'name' => $this->name,
                'father_or_husband_name' => $this->father_or_husband_name,
                'phone' => $this->phone ?: null,
                'email' => $this->email ?: null,
                'nid_or_passport_number' => $this->nid_or_passport_number ?: null,
                'address' => $this->address ?: null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Customer created successfully!'
            ]);

            return redirect()->route('admin.customers.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error creating customer: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.customers.create');
    }
}
