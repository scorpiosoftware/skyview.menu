<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Restaurant Menu') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @stack('scripts')
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[#F8F5F0] text-[#2C1810] min-h-screen">
    <header class="w-full lg:max-w-4xl max-w-[335px] mx-auto px-6 py-6 border-b border-[#D4B996]/30">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('media/images/logo.png') }}" class="box-content w-36" alt="">
                {{-- <h1 class="text-2xl font-semibold text-[#8B4513]">{{ config('app.name', 'Restaurant Menu') }}</h1> --}}
            </div>
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center px-4 py-2 text-[#8B4513] bg-white border border-[#D4B996] hover:bg-[#D4B996] hover:text-white transition-colors duration-200 rounded-full text-sm font-medium shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('navigation.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-4 py-2 text-[#8B4513] hover:bg-white/50 transition-colors duration-200 rounded-full text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            {{ __('navigation.log_in') }}
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-4 py-2 text-[#8B4513] bg-white border border-[#D4B996] hover:bg-[#D4B996] hover:text-white transition-colors duration-200 rounded-full text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                {{ __('navigation.register') }}
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="w-full lg:max-w-4xl  mx-auto px-6 mt-8">
        @livewire('dining-choice-modal')

        @livewire('menu')
        @livewire('cart')
        @livewire('checkout')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('cartUpdated', (message) => {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: window.toastTimer || 1500 ,// Default to 1500ms if not configured  
                        timerProgressBar: true,
                    });
                });
                Livewire.on('clearCart', () => {
                    Swal.fire({
                        // toast: true,
                        position: 'center',
                        icon: 'success',
                        title: '{{ __('alert.order_placed') }}',
                        timer: window.toastTimer || 1500 ,// Default to 1500ms if not configured  
                        timerProgressBar: true,
                    });
                });
            });
        </script>
    </main>

    @livewireScripts
</body>

</html>
