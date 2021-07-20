<x-back-container>
    @livewire('backend.widgets.lists',[
        'widget' =>$widget,
        'prefix' => 'list'
    ])
    @livewire('backend.widgets.quickform',[
        'widget' =>null,
    ])

    @livewire('backend.widgets.relation_form',['parentSessionKey'=>''])

</x-back-container>
