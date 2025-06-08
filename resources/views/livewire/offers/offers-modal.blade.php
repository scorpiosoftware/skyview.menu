<div x-data="dragDropHandler()">

    <!-- offer Modal -->
    {{-- @entangle('showModal') --}}
    <div x-data="{ show: @entangle('showModal') }" x-show="show" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="offer-modal" role="dialog" aria-modal="true">

        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel -->
        <div class="flex md:min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-full md:max-w-4xl sm:p-6">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button @click="show = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                        <span class="sr-only">{{ __('offer.close') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">{{ __('offer.title') }}</h3>
                        <div class="grid md:grid-cols-2 gap-x-2">
                            <form wire:submit.prevent='save'>
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700">{{ __('offer.name') }}</label>
                                    <input type="text" wire:model="name" id="name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="pt-2">
                                    <label for="discount"
                                        class="block text-sm font-medium text-gray-700">{{ __('offer.discount') }}</label>
                                    <input type="number" step="0.01" wire:model="discount" id="discount"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('discount')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex items-center justify-between pt-2">
                                    <div>
                                        <label for="startDate"
                                            class="block text-sm font-medium text-gray-700">{{ __('offer.startDate') }}</label>
                                        <input type="date" wire:model="startDate" id="startDate"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('startDate')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="endDate"
                                            class="block text-sm font-medium text-gray-700">{{ __('offer.endDate') }}</label>
                                        <input type="date" wire:model="endDate" id="endDate"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('endDate')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="pt-2">
                                    <label for="image"
                                        class="block text-sm font-medium text-gray-700">{{ __('offer.image') }}</label>
                                    <div class="mt-1 flex items-center space-x-4">
                                        <div class="flex-1">
                                            <input type="file" wire:model="image" id="image" accept="image/*"
                                                class="mt-1 block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100">
                                            @error('image')
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
                                <div class="pt-2 px-2 flex justify-end items-center space-x-3">
                                    <label for="active">{{ __('offer.active') }}</label>
                                    <input type="checkbox" name="" wire:model='active' id="">
                                </div>
                                <div class="flex-1 pt-4">
                                    <div class="mb-6">
                                        <h4 class="font-medium mb-2">{{ __('offer.items') }}</h4>
                                        <div class="min-h-32 p-4 border-2 border-dashed border-gray-300 rounded-lg bg-white transition-all duration-300"
                                            @dragover.prevent="dragOver($event, 'cart')" @dragleave="dragLeave($event)"
                                            @drop.prevent="drop($event, 'cart')"
                                            :class="{
                                                'border-blue-500 bg-blue-50': dropZone === 'cart',
                                                'border-green-500 bg-green-50': dropSuccess
                                            }">
                                            <div class="text-center text-gray-500" x-show="!cartItems.length">
                                                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H17M9 19a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4z">
                                                    </path>
                                                </svg>
                                                {{ __('offer.drop') }}
                                            </div>
                                            <!-- Cart Items -->
                                            <div x-show="cartItems.length" class="space-y-2">
                                                <template x-for="item in cartItems" :key="item.id">
                                                    <div class="flex items-center gap-3 p-2 bg-gray-50 rounded">
                                                        <img :src="item.image" class="w-8 h-8 object-contain">
                                                        <span x-text="item.name" class="flex-1 text-sm"></span>
                                                        <button @click="removeFromCart(item.id)"
                                                            class="text-red-500 hover:text-red-700">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <button type="submit"
                                    class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-emerald-500/50">
                                    {{ __('offer.save') }}
                                </button>
                            </form>

                            <div class="p-2 w-full shadow-lg" x-data="{ loading: false }" x-init="@this.on('products-updated', () => {
                                loading = true;
                                setTimeout(() => loading = false, 100);
                            })">
                                <div class="">
                                    <select class="w-full rounded-lg border-red-50" name="selectedCategory"
                                        wire:model.live='selectedCategory' id="">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ App::getLocale() == 'ar' ? $category->name : $category->other_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-3 gap-2 p-2 overflow-auto max-h-60 " x-show="!loading"
                                    x-transition:enter="transition transform ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-x-4"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                    x-transition:leave="transition transform ease-in duration-300"
                                    x-transition:leave-start="opacity-100 translate-x-0"
                                    x-transition:leave-end="opacity-0 -translate-x-4">
                                    @foreach ($products as $item)
                                        <div class="w-full relative transition-all duration-300 hover:scale-105"
                                            draggable="true"
                                            @dragstart="dragStart($event, {
                                                       id: {{ $item->id }},
                                                       name: '{{ $item->name }}',
                                                       image: '{{ $item->image }}',
                                                       price: {{ $item->price ?? 0 }}
                                                       })"
                                            @dragend="dragEnd($event)"
                                            :class="{ 'opacity-50': dragging && draggedId === {{ $item->id }} }">
                                            <img class="object-contain" src="{{ asset($item->image) }}"
                                                alt="">
                                            <div
                                                class="text-xs absolute bottom-0 bg-transparent font-bold w-full shadow-sm text-center py-1 overflow-auto">
                                                {{ $item->name }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Loading state -->
                                <div x-show="loading" x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    class="col-span-3 flex justify-center items-center py-8 fixed top-0 left-0 min-h-full min-w-full">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function dragDropHandler() {
            return {
                dragging: false,
                draggedItem: null,
                draggedId: null,
                dropZone: null,
                dropSuccess: false,
                cartItems: [],
                favoriteItems: [],

                dragStart(event, item) {
                    this.dragging = true;
                    this.draggedItem = item;
                    this.draggedId = item.id;

                    event.dataTransfer.setData('text/plain', JSON.stringify(item));
                    event.dataTransfer.effectAllowed = 'copy';
                },

                dragEnd(event) {
                    this.dragging = false;
                    this.draggedItem = null;
                    this.draggedId = null;
                    this.dropZone = null;
                },

                dragOver(event, zone) {
                    this.dropZone = zone;
                    event.dataTransfer.dropEffect = 'copy';
                },

                dragLeave(event) {
                    if (!event.currentTarget.contains(event.relatedTarget)) {
                        this.dropZone = null;
                    }
                },

                init() {

                    Livewire.on('reset-cart', () => {
                        this.cartItems.forEach(element => {
                            this.removeFromCart(element.id);
                        });
                    });
                    Livewire.on('append-items', (cartData) => {
                        this.cartItems.forEach(element => {
                            this.removeFromCart(element.id);
                        });
                        this.cartItems = cartData[0];
                    });
                },

                async drop(event, zone) {
                    const itemData = JSON.parse(event.dataTransfer.getData('text/plain'));

                    this.dropSuccess = true;
                    setTimeout(() => this.dropSuccess = false, 1000);

                    if (zone === 'cart') {
                        if (!this.cartItems.find(item => item.id === itemData.id)) {
                            this.cartItems.push(itemData);
                        }
                    } else if (zone === 'favorites') {
                        if (!this.favoriteItems.find(item => item.id === itemData.id)) {
                            this.favoriteItems.push(itemData);
                        }
                    }

                    try {
                        await @this.call('handleDrop', {
                            item: itemData,
                            zone: zone,
                            action: 'add'
                        });
                    } catch (error) {
                        console.error('Error sending to Livewire:', error);
                    }

                    this.dropZone = null;
                },

                async removeFromCart(itemId) {
                    this.cartItems = this.cartItems.filter(item => item.id !== itemId);
                    await @this.call('handleDrop', {
                        item: {
                            id: itemId
                        },
                        zone: 'cart',
                        action: 'remove'
                    });
                },

                async removeFromFavorites(itemId) {
                    this.favoriteItems = this.favoriteItems.filter(item => item.id !== itemId);
                    await @this.call('handleDrop', {
                        item: {
                            id: itemId
                        },
                        zone: 'favorites',
                        action: 'remove'
                    });
                }
            }
        }
    </script>
@endpush
