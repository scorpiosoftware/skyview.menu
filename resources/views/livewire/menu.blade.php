<div>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <div class="sticky top-0 z-50 bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="md:flex justify-center items-center space-x-4">
                    @if ($isAdmin)
                        <div class="flex items-center justify-center space-x-2 mt-2 mb-2">
                            <button wire:click="openProductModal"
                                class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md border border-green-400/20">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="text-sm font-medium">{{ __('menu.add_product') }}</span>
                            </button>
                            <button wire:click="openCategoryModal"
                                class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md border border-blue-400/20">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="text-sm font-medium">{{ __('menu.add_category') }}</span>
                            </button>
                            <button wire:click='editCategory({{ $selectedCategory }})'
                                class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md border border-blue-400/20">
                                <span class="text-sm font-medium">{{ __('menu.edit_category') }}</span>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">

                    <div class="relative w-full sm:w-64">
                        <input type="text" wire:model.live="search" placeholder="{{ __('menu.search') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <svg class="w-5 h-5 absolute right-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div class="inline-flex items-center bg-gray-100 dark:bg-gray-800 rounded-full p-1 shadow-inner">
                        <!-- English Button -->
                        <button wire:click="changeLocale('en')"
                            class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            :class="{
                                'bg-white text-blue-600 shadow dark:bg-gray-700 dark:text-blue-400': locale === 'en',
                                'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700': locale !== 'en'
                            }"
                            x-data="{ locale: $wire.entangle('currentLocale') }">
                            <span class="fi fi-gb mr-2"></span>
                            English
                        </button>

                        <!-- Divider -->
                        <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                        <!-- Arabic Button -->
                        <button wire:click="changeLocale('ar')"
                            class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            :class="{
                                'bg-white text-blue-600 shadow dark:bg-gray-700 dark:text-blue-400': locale === 'ar',
                                'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700': locale !== 'ar'
                            }"
                            x-data="{ locale: $wire.entangle('currentLocale') }" dir="rtl">
                            العربية
                            <span class="fi fi-sa ml-2"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-gray-100 border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    <div class="relative">
                        <div
                            class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none">
                        </div>
                        <div
                            class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none">
                        </div>
                        @if ($isAdmin)
                            <div x-data="{}" x-init="Sortable.create($refs.sortable, {
                                onEnd: async (event) => {
                                    let ids = [...$refs.sortable.children].map(el => el.dataset.id);
                                    @this.call('updateSortOrder', ids);
                                }
                            })" x-ref="sortable"
                                class="flex space-x-2 sm:space-x-4 overflow-x-auto custom-scrollbar pb-2">
                                <button wire:click="clearCategory"
                                    class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-full text-sm sm:text-base whitespace-nowrap {{ !$selectedCategory ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                                    {{ __('menu.all') }}
                                </button>
                                @foreach ($categories as $category)
                                    <button data-id="{{ $category->id }}"
                                        wire:click="selectCategory({{ $category->id }})"
                                        wire:key="category-{{ $category->id }}"
                                        class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-full text-sm sm:text-base whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                                        {{ App::getLocale() == 'ar' ? $category->name : $category->other_name }}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="flex space-x-2 sm:space-x-4 overflow-x-auto custom-scrollbar pb-2">
                                <button wire:click="clearCategory"
                                    class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-full text-sm sm:text-base whitespace-nowrap {{ !$selectedCategory ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                                    {{ __('menu.all') }}
                                </button>
                                @foreach ($categories as $category)
                                    <button wire:click="selectCategory({{ $category->id }})"
                                        wire:key="category-{{ $category->id }}"
                                        class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-full text-sm sm:text-base whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                                        {{ App::getLocale() == 'ar' ? $category->name : $category->other_name }}
                                    </button>
                                @endforeach
                            </div>
                        @endif


                    </div>
                </div>
            </div>
            <!-- offers -->
            {{-- @if (count($offers) > 0)
                <div class="bg-gray-100 border-t border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                        <div class="relative">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none">
                            </div>
                            <div
                                class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none">
                            </div>
                            <div class="flex space-x-2 sm:space-x-4 overflow-x-auto custom-scrollbar pb-2">
                                <button wire:click="clearOffer"
                                    class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-full text-sm sm:text-base whitespace-nowrap {{ !$selectedOffer ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                                    {{ __('menu.all') }}
                                </button>
                                @foreach ($offers as $offer)
                                    <button wire:click="selectOffer({{ $offer->id }})"
                                        wire:key="offer-{{ $offer->id }}"
                                        class="flex-shrink-0 px-3 sm:px-4 py-2 rounded-full text-sm sm:text-base whitespace-nowrap {{ $selectedOffer == $offer->id ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                                        {{ $offer->name }}
                                    </button>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            @endif --}}

        </div>

        <!-- Products Grid -->
        <div class="max-w-7xl sm:w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if ($isAdmin)
                <div class="mb-4 flex justify-end">
                    <button wire:click="toggleEditing"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 shadow-md border border-purple-400/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span
                            class="text-sm font-medium">{{ $isEditing ? __('menu.disable_editing') : __('menu.enable_editing') }}</span>
                    </button>
                </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 sm:gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transform transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
                        wire:key="product-{{ $product->id }}">
                        <div class="relative overflow-hidden group"
                            wire:click='openImageModal("{{ $product->image }}")'>
                            @if ($isEditing && $isAdmin)
                                <button wire:click="deleteProduct({{ $product->id }})"
                                    class="absolute bottom-2 right-2 z-20 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 transition-colors shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <button wire:click="editProduct({{ $product->id }})"
                                    class="absolute bottom-2 left-2 z-20 bg-blue-500 text-white p-1.5 rounded-full hover:bg-blue-600 transition-colors shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5h2M4 20h16M14.828 6.172a2 2 0 112.828 2.828l-8.486 8.486H6v-2.828l8.828-8.486z" />
                                    </svg>
                                </button>
                            @endif
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" lazy
                                class="w-full h-40 sm:h-48 z-50 object-cover transition-transform duration-500 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300">
                            </div>
                            <div class="absolute top-3 right-3 max-w-[40%]">
                                <span
                                    class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-md truncate">
                                    {{ __('cart.currency') }}
                                    {{-- {{ number_format($product->getDiscountedPriceAttribute(), 0) }} --}}
                                    {{-- {{ number_format($product->getDiscountedPriceForSize($selectedPrices[$product->id] ?? $product?->prices[0]?->size)  ?? $product->getDiscountedPriceAttribute(), 0) }} --}}
                                    @if (isset($selectedPrices[$product->id]))
                                        {{ number_format($product->getDiscountedPriceForSize($selectedPrices[$product->id]), 0) }}
                                    @else
                                        {{ number_format($product->getDiscountedPriceAttribute(), 0) }}
                                    @endif
                                </span>
                            </div>
                            <div class="absolute top-3 left-3 max-w-[40%]">
                                <span
                                    class="inline-block bg-white/90 backdrop-blur-sm text-gray-700 px-3 py-1 rounded-full text-sm font-medium shadow-sm truncate"
                                    title="{{ App::getLocale() == 'ar' ? $product->category->name : $product->category->other_name }}">
                                    {{ App::getLocale() == 'ar' ? $product->category->name : $product->category->other_name }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-start items-center gap-3 px-4 py-2">
                            @foreach ($product->prices as $price)
                                <button wire:click="selectSize({{ $product->id }}, '{{ $price->size }}')"
                                    class="px-2 py-0.5 w-auto text-center rounded-full border {{ isset($selectedPrices[$product->id]) && $selectedPrices[$product->id] == $price->size ? 'bg-red-500 text-white' : 'border-gray-300 bg-white' }} hover:bg-red-500 hover:text-white text-xs font-semibold transition duration-200 shadow-sm">
                                    {{ App::getLocale() == 'ar' ? $price->size : $price->size_en }}
                                </button>
                            @endforeach
                        </div>

                        <div class="p-4 flex flex-col flex-grow">
                            <h3
                                class="text-lg font-bold text-gray-900 mb-2 transition-colors duration-300 group-hover:text-blue-600">
                                {{ App::getLocale() == 'ar' ? $product->name : $product->other_name }}
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-2 h-10 mb-4">
                                {{ App::getLocale() == 'ar' ? $product->description : $product->other_description }}
                            </p>
                            <div class="mt-auto">
                                <button
                                    wire:click="$dispatch('addToCart', { productId: {{ $product->id }} , size: 
                                    @if (isset($selectedPrices[$product->id])) `{{ $selectedPrices[$product->id] }}`
                                    @else
                                    {{ 'null' }} @endif })"
                                    class="w-full bg-blue-500 text-white py-2.5 px-4 rounded-full hover:bg-blue-600 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 font-medium">
                                    {{ __('product.add_to_cart') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- @if ($products->count() > $perPage) --}}
            <div class="mt-6 !bg-white rounded-lg p-4">
                {{ $products->links() }}
            </div>
            {{-- @endif --}}
        </div>

        <!-- Cart Button -->
        <div class="fixed bottom-4 sm:bottom-6 right-4 sm:right-6">
            <button wire:click="$dispatch('toggleCart')"
                class="bg-blue-500 text-white p-3 sm:p-4 rounded-full shadow-lg hover:bg-blue-600 transition-colors">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
        </div>
    </div>

    @if ($showImageModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <div class="absolute right-0 top-0 pr-4 pt-4">
                                <button wire:click="closeImageModal"
                                    class="rounded-md bg-white text-red-400 border hover:text-gray-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900">{{ __('menu.product_image') }}</h3>
                            <img src="{{ $selectedImage }}" alt="{{ __('menu.product_image') }}"
                                class="w-full h-full  object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Product Modal -->
    @if ($showProductModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="createProduct">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('menu.product') }}</h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="productName"
                                        class="block text-sm font-medium text-gray-700">{{ __('menu.product_name') }}</label>
                                    <input type="text" wire:model="productName" id="productName"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('productName')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex justify-start items-center w-full space-x-2">
                                    <div>
                                        <label for="newSize"
                                            class="block text-sm font-medium text-gray-700">{{ __('menu.size') }}</label>
                                        <input type="text" wire:model="newSize" id="newSize"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('newSize')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="newSize_en"
                                            class="block text-sm font-medium text-gray-700">{{ __('menu.size_en') }}</label>
                                        <input type="text" wire:model="newSize_en" id="newSize_en"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('newSize_en')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="newPrice"
                                            class="block text-sm font-medium text-gray-700">{{ __('menu.product_price') }}</label>
                                        <input type="number" wire:model="newPrice" id="newPrice"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('newPrice')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="button" wire:click='appendSizePrice'
                                        class="w-full mt-5 justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        {{ __('menu.append') }}
                                    </button>

                                </div>

                                <div>
                                    <table
                                        class="min-w-full text-sm text-left  text-gray-700 border rounded-lg shadow-lg">
                                        <thead class="text-xs uppercase bg-gray-100 text-gray-600">
                                            <tr>
                                                <th class="px-4 py-2 whitespace-nowrap cursor-pointer">
                                                    {{ __('menu.size') }}</th>
                                                <th class="px-4 py-2 whitespace-nowrap cursor-pointer">
                                                    {{ __('menu.size_en') }}</th>
                                                <th class="px-4 py-2 whitespace-nowrap cursor-pointer">
                                                    {{ __('menu.product_price') }}</th>
                                                <th>{{ __('admin-panel.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="max-h-12 overflow-y-scroll ">
                                            @foreach ($sizes as $key => $size)
                                                <tr class="border-b hover:bg-gray-50"
                                                    wire:key="size-{{ $key }}">
                                                    <td class="px-4 py-2">
                                                        {{ isset($size['size']) ? $size['size'] : null }}</td>
                                                    <td class="px-4 py-2">
                                                        {{ isset($size['size_en']) ? $size['size_en'] : null }}</td>
                                                    <td class="px-4 py-2">{{ $size['price'] }}</td>
                                                    <td>
                                                        <button type="button"
                                                            wire:click="removeSize({{ $key }})"
                                                            class="text-red-600 hover:underline text-sm">
                                                            {{ __('admin-panel.delete') }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                                <div>
                                    <label for="productOtherName"
                                        class="block text-sm font-medium text-gray-700">{{ __('menu.product_other_name') }}</label>
                                    <input type="text" wire:model="productOtherName" id="productOtherName"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('productOtherName')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="productDescription"
                                        class="block text-sm font-medium text-gray-700">{{ __('menu.product_description') }}</label>
                                    <textarea wire:model="productDescription" id="productDescription" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    @error('productDescription')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="productOtherDescription"
                                        class="block text-sm font-medium text-gray-700">{{ __('menu.product_other_description') }}</label>
                                    <textarea wire:model="productOtherDescription" id="productOtherDescription" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label for="productPrice"
                                        class="block text-sm font-medium text-gray-700">{{ __('menu.product_price') }}</label>
                                    <input type="number" wire:model="productPrice" id="productPrice"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('productPrice')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="productCategory"
                                        class="block text-sm font-medium text-gray-700">{{ __('menu.product_category') }}</label>
                                    <select wire:model="productCategory" id="productCategory"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">{{ __('menu.select_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('productCategory')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="productImage"
                                        class="block text-sm font-medium text-gray-700">{{ __('menu.product_image') }}</label>
                                    <div class="mt-1 flex items-center space-x-4">
                                        <div class="flex-1">
                                            <input type="file" wire:model="productImage" id="productImage"
                                                accept="image/*"
                                                class="mt-1 block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100">
                                            @error('productImage')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if ($tempImageUrl)
                                            <div class="flex-shrink-0">
                                                <img src="{{ $tempImageUrl }}" alt="Preview"
                                                    class="h-20 w-20 object-cover rounded-lg">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('menu.save') }}
                            </button>
                            <button type="button" wire:click="closeProductModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('menu.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Category Modal -->
    @if ($showCategoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="createCategory">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('menu.add_category') }}</h3>
                            </div>
                            <div>
                                <label for="categoryName"
                                    class="block text-sm font-medium text-gray-700">{{ __('menu.category_name') }}</label>
                                <input type="text" wire:model="categoryName" id="categoryName"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('categoryName')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="categoryOtherName"
                                    class="block text-sm font-medium text-gray-700">{{ __('menu.category_other_name') }}</label>
                                <input type="text" wire:model="categoryOtherName" id="categoryOtherName"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('menu.save') }}
                            </button>
                            <button type="button" wire:click="closeCategoryModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('menu.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E1 transparent;
        }
    </style>
</div>
