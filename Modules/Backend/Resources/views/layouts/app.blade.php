

<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Windmill Dashboard') }} | @yield('title', 'Windmill')</title>

    {{-- Fonts --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" /> --}}

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @livewireStyles
    @stack('styles')
    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{ asset('js/init-alpine.js') }}"></script>
    @livewireEditorjsScripts
    <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "https://hm.baidu.com/hm.js?82143af04f2fe5478f69ce92966fcba2";
          var s = document.getElementsByTagName("script")[0];
          s.parentNode.insertBefore(hm, s);
        })();
    </script>

</head>

<body>
    @if (session('status') || request()->query('verified'))
        <div x-data="{alert: true}" x-show="alert" class="fixed z-30 top-5 left-5">
            <div x-show="alert" @click.away="alert = false"
                class="border-green-600 bg-green-200  border-t-4 text-green-600 rounded px-4 py-3 shadow-md"
                role="alert" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div class="flex">
                    <div>
                        <p class="font-bold">{{ __('Alert') }}</p>
                        <p class="text-sm">
                            {{ request()->query('verified') ? __('Email verified') : session('status') }}
                        </p>
                    </div>
                    <button @click="alert = false" class="flex items-start focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">

        @include('partials.sidebar.main-sidebar')

        <div class="flex flex-col flex-1 w-full">

            @include('partials.navbar.main-navbar')

            <main class="h-full overflow-y-auto">
                {{$slot}}

                {{-- <div class="container px-6 mx-auto grid">



                </div> --}}
            </main>
        </div>
    </div>
    @stack('modals')

    @livewireScripts
    @stack('scripts')
</body>

</html>
