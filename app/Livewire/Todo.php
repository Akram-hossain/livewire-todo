<?php

namespace App\Livewire;

use App\Models\Todo as ModelsTodo;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Todo extends Component
{
    use \Livewire\WithPagination;

    #[Rule('required|min:5|max:50')]
    public $name;

    public $search;

    public function create()
    { 
        $validated = $this->validateOnly('name');

        ModelsTodo::create($validated);

        $this->reset('name');

        session()->flash('success', 'Todo created successfully');
    }

    public function render()
    {
        return view('livewire.todo', [
            'todos' => ModelsTodo::where('name', 'like', '%' . $this->search . '%')->paginate(5)
        ]);
    }
}
