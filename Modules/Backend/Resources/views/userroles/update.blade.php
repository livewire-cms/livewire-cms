<x-back-container>
    {{-- {{dd($widget)}} --}}


    @php
    // $cc->relationRender('worlds');
    // $cc->relationRender('categories');
    // dd($widget);
    $loadRelations = [];
    @endphp

    <x-back-form :widget="$widget" :loadRelations="$loadRelations" :cc="$cc"></x-back-form>

</x-back-container>
