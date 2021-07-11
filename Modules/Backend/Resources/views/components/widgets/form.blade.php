<div>

    @php
        $widget = $attributes->get('widget');
        // dd($widget);
        // $widget->form->render();
        //更新时候用
        $cc = $attributes->get('cc');
        $loadRelations = $attributes->get('loadRelations',[]);


    @endphp


    @livewire('backend.widgets.form',[
        'widget' =>$widget,
        'loadRelations' => $loadRelations,
    ])
    @livewire('backend.widgets.relation_form')

    @foreach ($loadRelations as $loadRelation)

    @php

        if(is_object($cc)){
            $cc->relationRender($loadRelation);
            // dd($cc);
        }

    @endphp
    @livewire('backend.widgets.relation_lists',[
        'widget' =>$widget,
        'prefix' => $loadRelation
    ])
    @endforeach



</div>
