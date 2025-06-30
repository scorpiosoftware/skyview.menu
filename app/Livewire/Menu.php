<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Facades\App;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Menu extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $perPage = 12;
    public $selectedPrices = [];
    public $maxSizeCount = 4;
    public $sizes = [];
    public $newSize;
    public $newSize_en;
    public $newPrice;
    public $showImageModal = false;
    public $selectedImage = null;
    public $currentLocale;
    public $search = '';
    public $selectedCategory = 0;
    public $selectedOffer = 0;
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

    public function appendSizePrice()
    {
        if ($this->newSize == null || $this->newSize_en == null  || $this->newPrice == null || count($this->sizes) >= $this->maxSizeCount)
            return;
        array_push($this->sizes, [
            'size' => $this->newSize,
            'size_en' => $this->newSize_en,
            'price' => $this->newPrice,
        ]);
    }

    public function selectSize($productId, $price)
    {
        $this->selectedPrices[$productId] = $price;
    }
    #[On('offerSelected')]

    public function getOffer($id)
    {

        // $this->selectedOffer = $id;
    }

    public function editCategory($id = 0)
    {
        if ($id <= 0)
            return;
        $category = Category::find($id);
        $this->categoryName = $category->name;
        $this->categoryOtherName = $category->other_name;
        $this->showCategoryModal = true;
    }

    public function renderProducts()
    {
        $query = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })->when($this->selectedOffer && $this->selectedOffer != 0, function ($query) {
                $query->whereHas('offers', function ($query) {
                    $query->where('offers.id', $this->selectedOffer);
                });
            });

        return $query;
    }

    public function initSize()
    {
        $query = $this->renderProducts();
        $products = $query->paginate($this->perPage);
        foreach ($products as $product) {
            if (count($product->prices) <= 0)
                continue;
            $firstSize = $product->prices->first()->size;
            $this->selectSize($product->id, $firstSize);
        }
    }
    public function render()
    {
        $query = $this->renderProducts();
        $products = $query->paginate($this->perPage);
        $categories = Category::orderBy('sort_order')->get();
        return view('livewire.menu', [
            'products' => $products,
            'categories' => $categories,
            'offers' => Offer::current()->get(),
            'isAdmin' => Auth::check() && Auth::user()->isAdmin(),
        ]);
    }

    public function updateSortOrder($orderedIds)
    {
        
        foreach ($orderedIds as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index + 1]);
        }
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function selectOffer($offerId)
    {
        $this->selectedOffer = $offerId;
        $this->resetPage();
    }
    public function clearOffer()
    {
        $this->selectedOffer = null;
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
        $this->selectedCategory = null;
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
        $this->tempImageUrl = $product->image;
        $prices = $product->prices;
        foreach ($prices as $key => $value) {
            array_push($this->sizes, [
                'size' => $value->size,
                'size_en' => $value->size_en,
                'price' => $value->price,
            ]);
        }
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
        $imagePath = null;
        if ($this->productImage instanceof TemporaryUploadedFile) {
            $imagePath = $this->productImage->store('products', 'public');
        }

        info($imagePath);

        $product =  Product::updateOrCreate([
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

        if ($product) {
            // Use syncSizePrices instead of loop with addSizePrice
            $product->syncSizePrices($this->sizes);
        }

        $this->sizes = [];
        $this->reset();
        $this->dispatch('product-created', __('menu.product_created'));
        $this->closeProductModal();
    }

    public function removeSize($key)
    {
        if (isset($this->sizes[$key])) {
            unset($this->sizes[$key]);
            // Re-index the array to avoid gaps
            $this->sizes = array_values($this->sizes);
        }
    }
    public function createCategory()
    {
        $this->validate([
            'categoryName' => [
                'required',
                'min:3',
                Rule::unique('categories', 'name')->ignore($this->selectedCategory),
            ],
            'categoryOtherName' => 'required|min:3',
        ]);

        Category::updateOrCreate([
            'id' => $this->selectedCategory,
        ], [
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
        $this->initSize();
    }

    public function changeLocale($locale)
    {
        App::setLocale($locale);
        $this->currentLocale = $locale;
        $this->dispatch('locale-changed', locale: $locale);
    }
}
