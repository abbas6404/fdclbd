<?php

namespace App\Livewire\Admin\Flat;

use Livewire\Component;
use App\Models\ProjectFlat;

class Show extends Component
{
    public $flat;
    public $flatId;

    public function mount($id)
    {
        $this->flatId = $id;
        $this->flat = ProjectFlat::with(['project', 'createdBy', 'updatedBy'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.flat.show');
    }
}

