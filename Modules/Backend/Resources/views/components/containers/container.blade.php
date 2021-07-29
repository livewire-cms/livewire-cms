<x-back-app-layout>


    @section('title')
        @yield('title')
    @endsection





    @isset ($header)
        {!! $header !!}
    @endif

    {{ $slot }}


</x-back-app-layout>
