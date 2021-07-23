<div>
    @php
        $widget = $attributes->get('widget');
    @endphp


    @livewire('backend.widgets.form',[
        'widget' =>$widget,
        'loadRelations' => [],
    ])


    @livewire('backend.widgets.relation_form',['parentSessionKey'=>$widget->form->getSessionKey()])





</div>
