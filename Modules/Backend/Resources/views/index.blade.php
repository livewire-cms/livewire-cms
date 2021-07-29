<x-back-container>
    @section('title')
        {{__(current($widget)->getController()->pageTitle)}}
    @endsection
    @livewire('backend.livewire.widgets.lists',[
        'widget' =>$widget,
        'prefix' => 'list'
    ])
    @livewire('backend.livewire.widgets.quickform',[
        'widget' =>null,
    ])

    @livewire('backend.livewire.widgets.relation_form',['parentSessionKey'=>''])

</x-back-container>
