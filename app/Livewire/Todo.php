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

    public $editingId;

    #[Rule('required|min:5|max:50')]
    public $editingName;

    public function create()
    { 
        $validated = $this->validateOnly('name');

        ModelsTodo::create($validated);

        $this->reset('name');

        session()->flash('success', 'Todo created successfully');

        $this->resetPage();
    }

    public function delete(ModelsTodo $todo)
    {
        $todo->delete();
        session()->flash('success', 'Todo deleted successfully');
    }

    public function toggle(ModelsTodo $todo)
    {
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function edit(ModelsTodo $todo)
    {
        $this->editingId = $todo->id; 
        $this->editingName = $todo->name;
    }

    public function update()
    {
        $this->validateOnly('editingName');

        ModelsTodo::find($this->editingId)->update([
            'name' => $this->editingName
        ]);

        $this->cancel();
    }

    public function cancel()
    {
        $this->reset('editingId','editingName');
    }

    public function render()
    {
        return view('livewire.todo', [
            'todos' => ModelsTodo::where('name', 'like', '%' . $this->search . '%')->paginate(5)
        ]);
    }
}
