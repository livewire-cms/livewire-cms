<x-back-container>
    {{-- {{dd($widget)}} --}}

    {{-- @livewire('backend.widgets.form',[
        'widget' =>$widget,
    ]) --}}

@php
// $cc->relationRender('worlds');
// $cc->relationRender('categories');
// dd($widget);
$loadRelations = ['worlds','categories'];
@endphp

<x-back-form :widget="$widget" :loadRelations="$loadRelations" :cc="$cc"></x-back-form>

</x-back-container>
