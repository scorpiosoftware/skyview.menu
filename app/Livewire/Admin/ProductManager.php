<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProductManager extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $isEditing = false;
    public $product;
    public $name;
    public $description;
    public $price;
    public $image;
    public $category_id;
    public $tempImage;
    public $categories;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'required',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'tempImage' => 'nullable|image|max:1024'
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'price', 'image', 'category_id', 'tempImage']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
        ];

        if ($this->tempImage) {
            $data['image'] = $this->tempImage->store('products', 'public');
        }

        if ($this->isEditing) {
            $this->product->update($data);
            session()->flash('message', 'Product updated successfully.');
        } else {
            Product::create($data);
            session()->flash('message', 'Product created successfully.');
        }

        $this->showModal = false;
    }

    public function delete(Product $product)
    {
        $product->delete();
        session()->flash('message', 'Product deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.product-manager', [
            'products' => Product::with('category')->latest()->get()
        ]);
    }
} 