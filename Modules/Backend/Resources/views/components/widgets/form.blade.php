<div>
    @php
        $widget = $attributes->get('widget');
    @endphp


    @livewire('backend.widgets.form',[
        'widget' =>$widget,
        'loadRelations' => [],
    ],key(\Str::random(10)))


    @livewire('backend.widgets.relation_form',[
        'parentSessionKey'=>$widget->form->getSessionKey()
    ],
    key(\Str::random(10)))





</div>
