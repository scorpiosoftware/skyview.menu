<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    public $showModal = false;
    public $isEditing = false;
    public $category;
    public $name;

    protected $rules = [
        'name' => 'required|min:3|unique:categories,name'
    ];

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name)
        ];

        if ($this->isEditing) {
            $this->category->update($data);
            session()->flash('message', 'Category updated successfully.');
        } else {
            Category::create($data);
            session()->flash('message', 'Category created successfully.');
        }

        $this->showModal = false;
    }

    public function delete(Category $category)
    {
        if ($category->products()->count() > 0) {
            session()->flash('error', 'Cannot delete category with associated products.');
            return;
        }
        
        $category->delete();
        session()->flash('message', 'Category deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::withCount('products')->latest()->get()
        ]);
    }
} 