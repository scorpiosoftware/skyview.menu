<div class="p-4 sm:p-6 space-y-4">
    <form wire:submit.prevent='save' class="flex justify-between items-center mb-4">
        <div>
            <input type="text" wire:model='table_number' name="" id="" autofocus>
            <span class="text-red-500 text-sm">
                @error('table_number')
                    {{ $message }}
                @enderror
            </span>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            {{ __('menu.save') }}
        </button>
    </form>
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700 border rounded-lg shadow-lg">
            <thead class="text-xs uppercase bg-gray-100 text-gray-600">
           
                <tr>
                 
                    <th class="px-4 py-2 whitespace-nowrap cursor-pointer">
                        {{ __('admin-panel.table_number') }}
                    </th>

                    <th class="px-4 py-2 whitespace-nowrap">{{ __('admin-panel.actions') }}</th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                       <th class="px-4 py-2 whitespace-nowrap"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tables as $table)
                    <tr class="border-b hover:bg-gray-50" wire:key="order-{{ $table->id }}">
                        <td class="px-4 py-2">{{ $table->table_number }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="delete({{ $table->id }})"
                                onclick="return confirm('Are you sure you want to delete this table?')"
                                class="text-red-600 hover:underline text-sm">
                                {{ __('product.delete') }}
                            </button>
                        </td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tables->links() }}
    </div>
</div>
