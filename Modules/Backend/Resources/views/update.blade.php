<x-back-container>
    @section('title')
        {{__(current($widget)->getController()->pageTitle)}}
    @endsection
    <x-back-form :widget="$widget"></x-back-form>
</x-back-container>
