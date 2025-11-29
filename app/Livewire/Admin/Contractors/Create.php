<?php

namespace App\Livewire\Admin\Contractors;

use Livewire\Component;
use App\Models\Contractor;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:contractors,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Contractor name is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
    ];

    public function save()
    {
        $this->validate();

        try {
            Contractor::create([
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'address' => $this->address ?: null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Contractor created successfully!'
            ]);

            return redirect()->route('admin.contractors.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error creating contractor: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.contractors.create');
    }
}
