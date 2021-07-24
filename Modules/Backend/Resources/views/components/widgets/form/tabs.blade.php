@php
    $tabs = $attributes->get('tabs',[]);
    $form = $attributes->get('form',[]);
    $tab_name = $attributes->get('tab_name');
    $widget = $attributes->get('widget',null);
    $relation_field = $attributes->get('relation_field',null);


@endphp

@if (count($tabs)>0)
<div class="w-full  h-auto my-4 " >
    <ul class="flex">
    @foreach ($tabs as $tab=>$tabFields)
        <li class="cursor-pointer py-2 px-4 text-gray-500 border-b-8"
            :class="{{$tab_name}}==='{{$tab}}' ? 'text-green-500 border-green-500' : ''" @click="{{$tab_name}} = '{{$tab}}'"
            >{{__($tab)}}</li>
    @endforeach
    </ul>
    <div class="w-full mx-auto border p-9">
        @foreach ($tabs as $tab=>$tabFields)
            <div x-show="{{$tab_name}}==='{{$tab}}'">
                <x-back-form-fields :fields="$tabFields" :form="$form" :relation_field="$relation_field" :widget="$widget"></x-back-form-fields>
            </div>
        @endforeach

    </div>

</div>

@endif
