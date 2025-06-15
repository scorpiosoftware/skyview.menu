<div class="p-4 sm:p-6 space-y-4">
    <!-- Search & Export -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <input type="text" wire:model.live="search" placeholder="{{ __('admin-panel.search') }}"
            class="border px-4 py-2 rounded w-full sm:w-1/3">

        <!-- Filters & Export -->
        <div class="flex flex-wrap gap-2 items-center">
            <!-- Status Filter -->
            <div class="relative w-60">
                <select wire:model.live="statusFilter"
                    class="appearance-none w-full border border-gray-300 bg-white text-gray-700 py-2 px-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">{{ __('admin-panel.all_statuses') }}</option>
                    <option value="pending">{{ __('admin-panel.pending') }}</option>
                    <option value="completed">{{ __('admin-panel.completed') }}</option>
                    <option value="cancelled">{{ __('admin-panel.cancelled') }}</option>
                </select>

                <!-- Custom dropdown arrow -->
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700 border rounded-lg shadow-lg">
            <thead class="text-xs uppercase bg-gray-100 text-gray-600">
                <tr>
                    @foreach (['id' => __('admin-panel.order_id'), 'name' => __('admin-panel.customer_name'), 'phone' => __('admin-panel.phone'), 'address' => __('admin-panel.address'), 'total' => __('admin-panel.total_amount'), 'created_at' => __('admin-panel.date'), 'status' => __('admin-panel.status')] as $field => $label)
                        <th class="px-4 py-2 whitespace-nowrap cursor-pointer"
                            wire:click="sortBy('{{ $field }}')">
                            {{ $label }}
                            @if ($sortField === $field)
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                    @endforeach
                    <th class="px-4 py-2 whitespace-nowrap">{{ __('admin-panel.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b hover:bg-gray-50" wire:key="order-{{ $order->id }}">
                        <td class="px-4 py-2">{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->name }}</td>
                        <td class="px-4 py-2">{{ $order->phone }}</td>
                        <td class="px-4 py-2">{{ $order->address }}</td>
                        <td class="px-4 py-2">{{ __('cart.currency') }} {{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-2">{{ $order->created_at }}</td>

                        <td class="px-4 py-2">
                            <div x-data="{ open: false, status: '{{ $order->status }}' }" class="relative">
                                <button @click="open = !open" class="px-2 py-1 rounded w-full text-sm font-medium"
                                    :class="{
                                        'bg-yellow-500 text-black': status === 'pending',
                                        'bg-green-600 text-white': status === 'completed',
                                        'bg-red-600 text-white': status === 'cancelled'
                                    }">
                                    <span x-text="status" class="capitalize"></span>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95" @click.away="open = false"
                                    class="absolute z-50 mt-2 w-32 bg-white border rounded shadow-lg">
                                    <ul class="py-1 z-50">
                                        <li><button
                                                @click="$wire.updateStatus({{ $order->id }}, 'pending'); status='pending'; open=false"
                                                class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-sm">{{ __('admin-panel.pending') }}</button>
                                        </li>
                                        <li><button
                                                @click="$wire.updateStatus({{ $order->id }}, 'completed'); status='completed'; open=false"
                                                class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-sm">{{ __('admin-panel.completed') }}</button>
                                        </li>
                                        <li><button
                                                @click="$wire.updateStatus({{ $order->id }}, 'cancelled'); status='cancelled'; open=false"
                                                class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-sm">{{ __('admin-panel.cancelled') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-2 flex gap-2">
                            <button wire:click='showOrderModal({{ $order->id }})'
                                class="text-blue-600 hover:underline text-sm">
                                {{ __('admin-panel.view_order') }}
                            </button>
                            <button wire:click="deleteOrder({{ $order->id }})"
                                class="text-red-600 hover:underline text-sm">
                                {{ __('admin-panel.delete_order') }}
                            </button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    <livewire:order-details :showModal='false' />
</div>
