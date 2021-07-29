<x-back-container>

    @section('title')
        {{__(current($widget)->getController()->pageTitle)}}
    @endsection
    @livewire('backend.livewire.widgets.reportcontainer',['widget'=>$widget])


</x-back-container>
