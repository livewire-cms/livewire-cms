
@php
$fields = $attributes->get('fields',[]);
$form = $attributes->get('form',[]);
@endphp

@foreach ($fields as $k=>$field)
    @php

    // dd($field);
    if($field['span']=='full'){
        $w = 'w-full';
    }elseif ($field['span']=='left') {
        $w = 'w-1/2';
    }elseif ($field['span']=='right') {
        $w = 'w-1/2';
    }
    // dd($fields)
    // if(is_object(trans($field['label']))){
    //     dd(trans($field['label']));
    // }

     @endphp

    <div class="{{$w}}  h-auto ">
        <div class="space-y-2">

            <label
            for="{{$field['id']}}"
            class="block font-medium tracking-tight"
            >

            @php



            @endphp 
            @if ($field['label'])
                <?= e(trans($field['label']))?>
            @endif
           

            </label
            >
            @if ($field['type']=='widget')
                {{-- @formwidget($field) --}}
                @if(isset($field['component']))
                    <x-dynamic-component :form="$form" :field="$field" :component="$field['component']" class="mt-4" />
                @else
                <x-back-form-widget>
                    {!! $field['html'] !!}123
                </x-back-form-widget>
                @endisset
             
            @elseif ($field['type']=='partial')
                <x-back-form-widget>
                    {!! $field['html'] !!}
                </x-back-form-widget>
            @elseif ($field['type']=='checkboxlist')

                <x-select.multiple wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.multiple>

            @elseif ($field['type']=='radio')

                <x-select.radio wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.radio>
            @elseif ($field['type']=='checkbox')
                <x-form.check-box wire:model.lazy="{{ $field['modelName']}}"  :prefix="str_replace('-','_',$field['id'])"></x-form.check-box>
          
            @elseif ($field['type']=='dropdown')
                <x-select.single wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.single>
            @elseif ($field['type']=='password')

            <input
            type="password"
            id="{{$field['id']}}"
            wire:model.lazy="{{ $field['modelName']}}"
            class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
            placeholder="{{$field['placeholder']}}"
            />
            @else

            <input
            id="{{$field['id']}}"
            wire:model.lazy="{{ $field['modelName']}}"
            class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
            placeholder="{{$field['placeholder']}}"
            />
            
            @endif
            {{-- {{dd($field)}} --}}
            @error($field['fieldName']) 
            <span class="text-xs text-red-500"
            >{{$message}}</span>
            @enderror
        </div>

    </div>

@endforeach





