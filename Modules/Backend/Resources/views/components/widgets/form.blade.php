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


    @livewire('backend.widgets.relation_form',['parentSessionKey'=>$widget->form->getSessionKey()])





</div>
