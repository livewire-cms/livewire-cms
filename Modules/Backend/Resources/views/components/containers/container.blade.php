<x-back-app-layout>

    @isset ($header)
        {!! $header !!}
    @endif

    {{ $slot }}


</x-back-app-layout>
