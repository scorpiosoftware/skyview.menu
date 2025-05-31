<div class="p-4 sm:p-6 space-y-4">
    <!-- Search & Export -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <input type="text" wire:model.live="search" placeholder="Search orders..."
            class="border px-4 py-2 rounded w-full sm:w-1/3">

        <!-- Filters & Export -->
        <div class="flex flex-wrap gap-2 items-center">
            <!-- Status Filter -->
            <div class="relative w-60">
                <select wire:model.live="statusFilter"
                    class="appearance-none w-full border border-gray-300 bg-white text-gray-700 py-2 px-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
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
                    @foreach (['id' => 'Order ID', 'name' => 'Full Name', 'phone' => 'Phone', 'address' => 'Address', 'total' => 'Total','created_at' => 'Created At', 'status' => 'Status'] as $field => $label)
                        <th class="px-4 py-2 whitespace-nowrap cursor-pointer"
                            wire:click="sortBy('{{ $field }}')">
                            {{ $label }}
                            @if ($sortField === $field)
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                    @endforeach
                    <th class="px-4 py-2 whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b hover:bg-gray-50" wire:key="{{ $order->id }}">
                        <td class="px-4 py-2">{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->name }}</td>
                        <td class="px-4 py-2">{{ $order->phone }}</td>
                        <td class="px-4 py-2">{{ $order->address }}</td>
                        <td class="px-4 py-2">IQD {{ number_format($order->total, 2) }}</td>
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
                                                class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-sm">Pending</button>
                                        </li>
                                        <li><button
                                                @click="$wire.updateStatus({{ $order->id }}, 'completed'); status='completed'; open=false"
                                                class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-sm">Completed</button>
                                        </li>
                                        <li><button
                                                @click="$wire.updateStatus({{ $order->id }}, 'cancelled'); status='cancelled'; open=false"
                                                class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-sm">Cancelled</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-2">
                            <button wire:click="deleteOrder({{ $order->id }})"
                                onclick="return confirm('Are you sure you want to delete this order?')"
                                class="text-red-600 hover:underline text-sm">
                                Delete
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
</div>
