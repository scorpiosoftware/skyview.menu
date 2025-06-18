<div>
    <!-- Cart Modal -->
    <div x-data="{ show: @entangle('showModal') }" 
         x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="cart-modal" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel -->
        <div class="flex md:min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button @click="show = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                        <span class="sr-only">{{ __('cart.close') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">{{ __('cart.title') }}</h3>
                        
                        @if(count($cartItems) > 0)
                            <div class="mt-4 flow-root">
                                <ul role="list" class="-my-6 divide-y divide-gray-200">
                                    @foreach($cartItems as $item)
                                        <li class="flex py-6">
                                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                <img src="{{ $item['product']['image'] }}" alt="{{ $item['product']['name'] }}" class="h-full w-full object-cover object-center">
                                            </div>

                                            <div class="ml-4 flex flex-1 flex-col">
                                                <div>
                                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                                        <h3>{{ $item['product']['name'] }} {{ $item['size'] }}</h3>
                                                        <p class="ml-4">{{ __('cart.currency') }} {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>{{ __('cart.subtotal') }}</p>
                                    <p>{{ __('cart.currency') }} {{ number_format($total, 2) }}</p>
                                </div>
                                {{-- <p class="mt-0.5 text-sm text-gray-500">{{ __('cart.shipping_and_taxes') }}</p> --}}
                                {{-- <div class="mt-6">
                                    <button wire:click="$dispatch('open-check-out')"  class="w-full rounded-md border border-transparent bg-blue-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700">
                                        {{ __('cart.checkout') }}
                                    </button>
                                </div> --}}
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('cart.empty_cart') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('cart.empty_cart_description') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

