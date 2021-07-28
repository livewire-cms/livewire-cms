

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
    @wireUiStyles
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @wireUiScripts

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
    <x-notifications z-index="z-auto"/>
    <x-dialog/>

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
