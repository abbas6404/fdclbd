<?php

namespace App\Livewire\Admin\Contractors;

use Livewire\Component;
use App\Models\Contractor;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $contractorId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';

    public function mount($id)
    {
        $this->contractorId = $id;
        $contractor = Contractor::findOrFail($id);
        
        $this->name = $contractor->name;
        $this->email = $contractor->email;
        $this->phone = $contractor->phone;
        $this->address = $contractor->address;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:contractors,email,' . $this->contractorId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];
    }

    protected $messages = [
        'name.required' => 'Contractor name is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
    ];

    public function update()
    {
        $this->validate();

        try {
            $contractor = Contractor::findOrFail($this->contractorId);
            
            $contractor->update([
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'address' => $this->address ?: null,
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Contractor updated successfully!'
            ]);

            return redirect()->route('admin.contractors.index');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error updating contractor: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.contractors.edit');
    }
}
