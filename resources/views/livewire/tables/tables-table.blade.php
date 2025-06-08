<div class="p-4 sm:p-6 space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('table.title') }}</h2>
                <p class="text-gray-600">{{ __('table.description') }}</p>
            </div>
            
            <!-- Add Table Form -->
            <form wire:submit.prevent='save' class="flex flex-col sm:flex-row gap-3 items-end">
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700">{{ __('table.table_number') }}</label>
                    <input 
                        type="text" 
                        wire:model='table_number'
                        placeholder="{{ __('table.enter') }}"
                        autofocus
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all w-full sm:w-auto"
                    >
                    <span class="text-red-500 text-xs block">
                        @error('table_number')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <button 
                    type="submit" 
                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hovertype="submit" 
                   class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
               >
                   {{ __('menu.save') }}
               </button>
           </form>
       </div>
   </div>

   <!-- Restaurant Floor Grid -->
   <div class="bg-white rounded-xl shadow-sm border p-6">
       <div class="mb-6">
           <div class="flex items-center justify-between mb-4">
               <h3 class="text-lg font-semibold text-gray-900">{{ __('table.layout') }}</h3>
               <div class="flex items-center gap-4 text-sm">
                   <div class="flex items-center gap-2">
                       <div class="w-4 h-4 bg-green-100 border-2 border-green-500 rounded"></div>
                       <span class="text-gray-600">{{ __('table.available') }}</span>
                   </div>
                   <div class="flex items-center gap-2">
                       <div class="w-4 h-4 bg-red-100 border-2 border-red-500 rounded"></div>
                       <span class="text-gray-600">{{ __('table.occuppied') }}</span>
                   </div>
               </div>
           </div>
           
           <!-- Grid Layout for Tables -->
           <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
               @foreach ($tables as $table)
                   <div class="table-item group" wire:key="table-{{ $table->id }}">
                       <div class="relative bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105">
                           <!-- Table Surface -->
                           <div class="w-full h-16 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl border-2 border-amber-300 shadow-inner mb-3 flex items-center justify-center">
                               <span class="font-bold text-amber-800 text-lg">{{ $table->table_number }}</span>
                           </div>
                           
                           <!-- Chairs around table -->
                           <div class="relative">
                               <!-- Top chairs -->
                               <div class="absolute -top-20 left-1/2 transform -translate-x-1/2 flex gap-1">
                                   {{-- <div class="w-4 h-6 bg-gradient-to-b from-gray-400 to-gray-600 rounded-t-lg"></div>s --}}
                                   <div class="w-4 h-6 bg-gradient-to-b from-gray-400 to-gray-600 rounded-t-lg"></div>
                               </div>
                               
                               <!-- Side chairs -->
                               <div class="absolute -top-10 -left-5 transform -translate-y-1/2">
                                   <div class="w-6 h-4 bg-gradient-to-r from-gray-400 to-gray-600 rounded-l-lg"></div>
                               </div>
                               <div class="absolute -top-10 -right-5 transform -translate-y-1/2">
                                   <div class="w-6 h-4 bg-gradient-to-l from-gray-400 to-gray-600 rounded-r-lg"></div>
                               </div>
                               
                               <!-- Bottom chairs -->
                               <div class="absolute -bottom-0 left-1/2 transform -translate-x-1/2 flex gap-1">
                                   {{-- <div class="w-4 h-6 bg-gradient-to-t from-gray-400 to-gray-600 rounded-b-lg"></div> --}}
                                   <div class="w-4 h-6 bg-gradient-to-t from-gray-400 to-gray-600 rounded-b-lg"></div>
                               </div>
                           </div>
                           
                           <!-- Status Badge (you can make this dynamic based on table status) -->
                           <div class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full shadow-lg">
                               {{ __('table.available') }}
                           </div>
                           
                           <!-- Delete Button -->
                           <button 
                               wire:click="delete({{ $table->id }})"
                               {{-- onclick="return confirm('Are you sure you want to delete this table?')" --}}
                               class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs transform hover:scale-110"
                           >
                               ×
                           </button>
                           
                           <!-- Table Info -->
                           {{-- <div class="text-center mt-2">
                               <p class="text-sm font-medium text-gray-700">Table {{ $table->table_number }}</p>
                               <p class="text-xs text-gray-500">4 seats</p>
                           </div> --}}
                       </div>
                   </div>
               @endforeach
           </div>
       </div>
   </div>

   <!-- Summary Statistics -->
   <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
       <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
           <div class="flex items-center justify-between">
               <div>
                   <p class="text-blue-100 text-sm">{{ __('table.total_tables') }}</p>
                   <p class="text-3xl font-bold">{{ $tables->count() }}</p>
               </div>
               <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                   <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                       <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                   </svg>
               </div>
           </div>
       </div>
       
       <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
           <div class="flex items-center justify-between">
               <div>
                   <p class="text-green-100 text-sm">{{ __('table.available') }}</p>
                   <p class="text-3xl font-bold">{{ $tables->where('status', 'available')->count() ?? $tables->count() }}</p>
               </div>
               <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                   <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                       <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                   </svg>
               </div>
           </div>
       </div>
       
       <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
           <div class="flex items-center justify-between">
               <div>
                   <p class="text-red-100 text-sm">{{ __('table.occuppied') }}</p>
                   <p class="text-3xl font-bold">{{ $tables->where('status', 'occupied')->count() ?? 0 }}</p>
               </div>
               <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                   <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                       <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                   </svg>
               </div>
           </div>
       </div>
   </div>

   <!-- Pagination -->
   <div class="mt-4">
       {{ $tables->links() }}
   </div>
</div>