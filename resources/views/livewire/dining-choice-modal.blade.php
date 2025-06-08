<div>
    {{-- resources/views/livewire/dining-choice-modal.blade.php --}}
    <!-- Success Message -->
    {{-- @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-[9999]">
            {{ session('message') }}
        </div>
    @endif --}}

    <!-- Modal Overlay -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999] p-4">
            <!-- Modal Content -->
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ __('entry.title') }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <p class="text-gray-600 text-center mb-6">
                        {{ __('entry.subtitle') }}
                    </p>

                    <!-- Dining Options -->
                    <div class="space-y-3">
                        <!-- Dine In Option -->
                        <button wire:click="selectChoice('Dine_In')"
                            class="w-full flex items-center justify-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all group">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-full group-hover:bg-orange-200 transition-colors">
                                <img src="{{ asset('media/images/dine_in.png') }}" class="w-6 h-6" alt="">
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600">
                                    {{ __('entry.dine_in') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('entry.dine_in_description') }}</p>
                            </div>
                        </button>

                        <!-- Takeaway Option -->
                        <button wire:click="selectChoice('Takeaway')"
                            class="w-full flex items-center justify-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all group">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full group-hover:bg-green-200 transition-colors">
                                <img src="{{ asset('media/images/take_away.png') }}" class="w-6 h-6" alt="">
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-gray-800 group-hover:text-green-600">
                                    {{ __('entry.take_away') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('entry.take_away_description') }}</p>
                            </div>
                        </button>
                        <br>
                        @if (count($offers) > 0)
                            <p class="text-gray-600 text-center mb-6">{{ __('offer.available_offers') }}</p>
                        @endif


                        @foreach ($offers as $offer)
                            <button wire:click="selectOffer({{ $offer->id }})"
                                class="w-full flex items-center justify-start space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all group">
                                <div
                                    class="flex items-center justify-center  bg-green-100 rounded-full group-hover:bg-green-200 transition-colors">
                                    <img src="{{ asset($offer->image) }}" class="box-border size-16" alt="">
                                </div>
                                <div class="text-left">
                                    <h3 class="font-semibold text-nowrap text-gray-800 group-hover:text-green-600">
                                        {{ $offer->name }}</h3>
                                    {{-- <p class="text-sm text-gray-500">{{ __('offer.startDate') }} :
                                        {{ $offer->start_date->format('Y-m-d') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('offer.endDate') }} :
                                        {{ $offer->end_date->format('Y-m-d') }}
                                    </p> --}}

                                    <p>حسم % {{ intval($offer->sale_percentage) }} </p>

                                </div>
                                <div class="flex justify-end items-center w-1/2" wire:ignore><livewire:countdown-timer
                                        :target-date="$offer->end_date->format('Y-m-d')" /></div>
                            </button>
                        @endforeach


                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 rounded-b-xl">
                    <button wire:click="closeModal"
                        class="w-full text-gray-500 hover:text-gray-700 transition-colors text-sm">
                        {{ __('entry.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>
