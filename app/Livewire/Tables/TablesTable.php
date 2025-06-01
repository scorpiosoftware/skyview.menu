<?php

namespace App\Livewire\Tables;

use App\Models\Table;
use Livewire\Component;
use Livewire\WithPagination;

class TablesTable extends Component
{
    use WithPagination;

    public $table_number;
    public function delete($id)
    {
        Table::find($id)?->delete();
    }
    public function save(){
        $this->validate([
            'table_number' => 'required|unique:tables,table_number',
        ]);
        Table::create([
            'table_number' => $this->table_number,
        ]);
        $this->dispatch('table-saved', ['message' => __('admin-panel.table_saved')]);
    }
    public function render()
    {
        $tables = Table::paginate(10);
        return view('livewire.tables.tables-table',compact('tables'));
    }
}
