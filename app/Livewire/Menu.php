<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\App;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Menu extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $perPage = 12;
    public $showImageModal = false;
    public $selectedImage = null;
    public $currentLocale;
    public $search = '';
    public $selectedCategory = null;
    public $showProductModal = false;
    public $showCategoryModal = false;
    public $isEditing = false;

    // Product form fields
    public $productId = null;
    public $productName = '';
    public $productOtherName = '';
    public $productDescription = '';
    public $productOtherDescription = '';
    public $productPrice = '';
    public $productCategory = '';
    public $productImage;
    public $tempImageUrl = null;

    // Category form fields
    public $categoryName = '';
    public $categoryOtherName = '';

    public function render()
    {
        $query = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            });

        return view('livewire.menu', [
            'products' => $query->paginate($this->perPage),
            'categories' => Category::all(),
            'isAdmin' => Auth::check() && Auth::user()->isAdmin(),
        ]);
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function clearCategory()
    {
        $this->selectedCategory = null;
        $this->resetPage();
    }

    public function openProductModal()
    {
        $this->showProductModal = true;
    }

    public function openImageModal($image)
    {
        $this->selectedImage = $image;
        $this->showImageModal = true;
    }

    public function openCategoryModal()
    {
        $this->showCategoryModal = true;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->reset(['productName', 'productOtherName', 'productDescription', 'productOtherDescription', 'productPrice', 'productCategory', 'productImage', 'tempImageUrl']);
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->reset(['selectedImage']);
    }

    public function closeCategoryModal()
    {
        $this->showCategoryModal = false;
        $this->reset(['categoryName']);
    }

    public function updatedProductImage()
    {
        $this->validate([
            'productImage' => 'image|max:1024', // max 1MB
        ]);

        $this->tempImageUrl = $this->productImage->temporaryUrl();
    }

    public function editProduct($id)
    {
        $product = Product::find($id);
        $this->productId = $product->id;
        $this->showProductModal = true;
        $this->productName = $product->name;
        $this->productOtherName = $product->other_name;
        $this->productDescription = $product->description;
        $this->productOtherDescription = $product->other_description;
        $this->productPrice = $product->price;
        $this->productCategory = $product->category_id;
        $this->productImage = $product->image;
    }

    public function createProduct()
    {
        $rules = $this->validate([
            'productName' => 'required|min:3',
            'productOtherName' => 'required|min:3',
            'productDescription' => 'required',
            'productOtherDescription' => 'required',
            'productPrice' => 'required|numeric|min:0',
            'productCategory' => 'required|exists:categories,id',
        ]);
        if ($this->productId === null) {
            $rules['productImage'] = 'required|image|max:1024';
        }
        info($this->productImage);
        $imagePath = null;
        if ($this->productImage instanceof TemporaryUploadedFile) {
            $imagePath = $this->productImage->store('products', 'public');
        }

        info($imagePath);

        Product::updateOrCreate([
            'id' => $this->productId,
        ], [
            'name' => $this->productName,
            'other_name' => $this->productOtherName,
            'description' => $this->productDescription,
            'other_description' => $this->productOtherDescription,
            'price' => $this->productPrice,
            'category_id' => $this->productCategory,
            'image' => $imagePath != null ? Storage::url(path: $imagePath) : $this->productImage,
            'slug' => Str::slug($this->productName),
        ]);

        $this->closeProductModal();
        $this->dispatch('product-created');
    }

    public function createCategory()
    {
        $this->validate([
            'categoryName' => 'required|min:3|unique:categories,name',
            'categoryOtherName' => 'required|min:3',
        ]);

        Category::create([
            'name' => $this->categoryName,
            'other_name' => $this->categoryOtherName,
            'slug' => Str::slug($this->categoryName),
        ]);

        $this->closeCategoryModal();
        $this->dispatch('category-created');
    }

    public function toggleEditing()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function deleteProduct($productId)
    {
        if (!$this->isEditing || !Auth::user()->isAdmin()) {
            return;
        }

        $product = Product::findOrFail($productId);

        // Delete the product image from storage
        if ($product->image) {
            $path = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($path);
        }

        $product->delete();
        $this->dispatch('product-deleted');
    }

    public function mount()
    {
        $this->currentLocale = App::getLocale();
    }

    public function changeLocale($locale)
    {
        App::setLocale($locale);
        $this->currentLocale = $locale;
        $this->dispatch('locale-changed', locale: $locale);
    }
}
