
@props(['field'=>$field,'form'=>$form,'widget'=>$widget])

<div wire:ignore>
@if($widget)
@php

    $loadRelation = ltrim($field['type'],'relation_');
    $relationController = $widget->form->getController()->relationRender($loadRelation);
    $vars = $relationController->vars;
    // dd($vars);
    $buttons = $vars['relationToolbarButtons'];
@endphp


<div class="flex">
    @foreach ($buttons as $key=>$button)
        @if ($key=='create')
            <div class="p-2">
                <button
                wire:click.prevent="onRelationButtonCreate('{{$loadRelation}}')"
                class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                >{{__($button)}}{{__($vars['relationLabel'])}}</button>
            </div>
        @endif
    @endforeach
</div>


@livewire('backend.livewire.widgets.relation_lists',[
    'widget' =>$widget,
    'prefix' => $loadRelation,
])

@endif
</div>
