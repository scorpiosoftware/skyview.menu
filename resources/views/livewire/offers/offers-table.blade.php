<div class="p-4 sm:p-6 space-y-4">
    <!-- Search & Export -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <input type="text" wire:model.live="search" placeholder="{{ __('admin-panel.search') }}"
            class="border px-4 py-2 rounded w-full sm:w-1/3">
        <button type="button" wire:click='showOfferModal'
            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-emerald-500/50">
            {{ __('offer.create') }}
        </button>
    </div>
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700 border rounded-lg shadow-lg">
            <thead class="text-xs uppercase bg-gray-100 text-gray-600">
                <tr>
                    @foreach (['image'=> __('admin-panel.offer_image'),'id' => __('admin-panel.offer_id'), 'name' => __('admin-panel.offer_name'), 'discount' => __('admin-panel.discount'), 'start_date' => __('admin-panel.startDate'), 'end_date' => __('admin-panel.endDate'), 'status' => __('admin-panel.status')] as $field => $label)
                        <th class="px-4 py-2 whitespace-nowrap cursor-pointer">
                            {{ $label }}
                        </th>
                    @endforeach
                    <th class="px-4 py-2 whitespace-nowrap">{{ __('admin-panel.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($offers as $offer)
                    <tr class="border-b hover:bg-gray-50" wire:key="offer-{{ $offer->id }}">
                        <td class="px-4 py-2"><img src="{{ $offer->image }}" class="w-12" alt=""></td>
                        <td class="px-4 py-2">{{ $offer->id }}</td>
                        <td class="px-4 py-2">{{ $offer->name }}</td>
                        <td class="px-4 py-2">{{ $offer->sale_percentage }}</td>
                        <td class="px-4 py-2">{{ $offer->start_date }}</td>
                        <td class="px-4 py-2">{{ $offer->end_date }}</td>
                        <td class="px-4 py-2"
                        
                        >{{ $offer->active ? __('admin-panel.active') :  __('admin-panel.inactive') }}</td>

                        <td class="px-4 py-2 mt-4 flex justify-start items-center gap-2">
                            <button wire:click='showOfferModal({{ $offer->id }})'
                                class="text-blue-600 hover:underline text-sm">
                                {{ __('admin-panel.edit') }}
                            </button>
                            <button wire:click="delete({{ $offer->id }})"
                                class="text-red-600 hover:underline text-sm">
                                {{ __('admin-panel.delete') }}
                            </button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $offers->links() }}
    </div>

    <livewire:offers.offers-modal>
</div>
