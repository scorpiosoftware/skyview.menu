<div>
    <div x-data="{
        show: @entangle('showModal'),
        mapInitialized: false,
        map: null,
        marker: null,
        initMap() {
            // Prevent re-initialization if map already exists
            if (this.map) {
                console.log('Map already initialized, skipping...');
                return;
            }
    
            const mapElement = document.getElementById('map');
            if (mapElement && typeof L !== 'undefined') {
                const lat = {{ $lat ?? 33.8938 }};
                const lng = {{ $lng ?? 35.5018 }};
    
                // Initialize map
                this.map = L.map('map').setView([lat, lng], 13);
    
                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);
    
                // Add draggable marker
                this.marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(this.map);
    
                // Handle marker drag
                this.marker.on('dragend', (e) => {
                    const { lat, lng } = e.target.getLatLng();
                    @this.set('lat', lat);
                    @this.set('lng', lng);
                });
    
                // Handle map click
                this.map.on('click', (e) => {
                    const { lat, lng } = e.latlng;
                    this.marker.setLatLng([lat, lng]);
                    @this.set('lat', lat);
                    @this.set('lng', lng);
                });
    
                console.log('Map initialized successfully');
            } else {
                console.error('Map element not found or Leaflet not loaded');
            }
        },
        updateMapLocation(lat, lng) {
            if (this.map && this.marker) {
                console.log('Updating map location to:', lat, lng);
                this.marker.setLatLng([lat, lng]);
                this.map.setView([lat, lng], 15); // Zoom in a bit more when geocoding
            } else {
                console.warn('Map or marker not available for update');
            }
        },
        resetMap() {
            if (this.map) {
                this.map.remove();
                this.map = null;
                this.marker = null;
                this.mapInitialized = false;
                console.log('Map reset');
            }
        }
    }" x-on:location-updated.window="updateMapLocation($event.detail.lat, $event.detail.lng)"
        @location-updated.window="updateMapLocation($event.detail.lat, $event.detail.lng)" x-show="show"
        x-init="$watch('show', value => {
            if (value && !mapInitialized) {
                setTimeout(() => {
                    initMap();
                    mapInitialized = true;
                    // Store references globally for Livewire events
                    window.mapInstance = map;
                    window.markerInstance = marker;
                }, 300); // wait for transition to finish
        
            }
        })" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="cart-modal" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="flex md:min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button @click="show = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">{{ __('checkout.title') }}</h3>
                    </div>
                </div>
                @if (!empty(session('site')))
                    <div class="mt-4 flow-root">
                        @if (session('site') == 'Dine_In')
                            <p class="text-sm text-gray-500">{{ __('checkout.dine_in') }}</p>
                            <form wire:submit.prevent="save"
                                class="max-w-md mx-auto p-6 bg-white shadow rounded-lg space-y-6">
                                <div>
                                    <label for="table" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('checkout.table_number') }}
                                    </label>
                                    <select id="table" name="table" wire:model="table"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>{{ __('checkout.select_table') }}
                                        </option>
                                        @foreach ($tables as $table)
                                            <option value="{{ $table->table_number }}">{{ $table->table_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('table')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-right">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md transition duration-200">
                                        {{ __('checkout.order_now') }}
                                    </button>
                                </div>
                            </form>
                        @elseif(session('site') == 'Takeaway')
                            <p class="text-sm text-gray-500">{{ __('checkout.take_away') }}</p>
                            <form wire:submit.prevent="save"
                                class="max-w-md mx-auto p-6 bg-white shadow rounded-lg space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('checkout.full_name') }}
                                    </label>
                                    <input id="name" type="text" name="name" wire:model="name"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('name')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('checkout.phone') }}
                                    </label>
                                    <input id="phone" type="text" name="phone" wire:model="phone"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('phone')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('checkout.address') }}
                                    </label>
                                    {{-- <input id="address" type="text" name="address" wire:model="address"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /> --}}
                                    <textarea wire:model="address" id="address" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    @error('address')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="note"
                                        class="block text-sm font-medium text-gray-700">{{ __('checkout.note') }}</label>
                                    <textarea wire:model="note" id="note" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    @error('note')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- <div>
                                    <input type="text" wire:model.live="address"
                                        placeholder="Search address..." class="border p-2 w-full">

                                    <div wire:ignore>
                                        <div id="map" style="height: 400px; margin-top: 10px;"></div>
                                    </div>

                                    <p class="mt-2">Latitude: {{ $lat }}, Longitude: {{ $lng }}</p>
                                </div> --}}

                                <div class="text-right">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md transition duration-200">
                                        {{ __('checkout.order_now') }}
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">{{ __('checkout.unknown_site') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Listen for Livewire events to update map from server side
        document.addEventListener('DOMContentLoaded', function() {
            // Modern Livewire event listener
            if (typeof Livewire !== 'undefined') {
                Livewire.on('location-updated', (lat, lng) => {
                    console.log('Livewire location-updated event received:', lat, lng);
                    
                    // Dispatch custom event to Alpine.js
                    window.dispatchEvent(new CustomEvent('location-updated', {
                        detail: { lat: lat, lng: lng }
                    }));
                });
            }
        });

        // Alternative event listener for older Livewire versions
        document.addEventListener('livewire:initialized', () => {
            if (typeof Livewire !== 'undefined') {
                Livewire.on('location-updated', (lat, lng) => {
                    console.log('Livewire location-updated event received (v2):', lat, lng);
                    
                    // Dispatch custom event to Alpine.js
                    window.dispatchEvent(new CustomEvent('location-updated', {
                        detail: { lat: lat, lng: lng }
                    }));
                });
            }
        });
    </script>
@endpush --}}
