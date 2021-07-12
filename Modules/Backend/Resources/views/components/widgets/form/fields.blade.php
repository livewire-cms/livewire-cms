
@php
$fields = $attributes->get('fields',[]);
$form = $attributes->get('form',[]);
@endphp

<div class="flex flex-wrap">
@foreach ($fields as $k=>$field)
    @php

    // dd($field);
    if($field['span']=='full'){
        $w = 'w-full';
        $grid = 'grid-cols-1';
    }elseif ($field['span']=='left') {
        $w = 'w-1/2';
        $grid = 'grid-cols-2';

    }elseif ($field['span']=='right') {
        $w = 'w-1/2';
        $grid = 'grid-cols-2';

    }
    // dd($fields)
    // if(is_object(trans($field['label']))){
    //     dd(trans($field['label']));
    // }

     @endphp
     {{-- <div class="w-full grid grid-cols-1">
        <div class="text-center">
            {{$field['label']}}
        </div>
     </div>
     <div class="w-1/2 grid grid-cols-1">
        <div class="text-center">
            {{$field['label']}}
        </div>
     </div>
     <div class="w-1/2 grid grid-cols-1">
        <div class="text-center">
            {{$field['label']}}
        </div>
     </div> --}}

     {{-- @if ($field['span']=='right')
        <div class="w-full"></div>
        <div class="w-1/2"></div>
    @endif --}}
    @if(isset($preField))
        @if($field['span']=='right')
            @if ($preField['span']=='full')
                <div class="w-1/2"></div>
            @endif
        @elseif($field['span']=='left')
            @if ($preField['span']=='left')
                <div class="w-full"></div>
            @endif
        @endif
    @else
        @if($field['span']=='right')
            <div class="w-full"></div>
            <div class="w-1/2"></div>
        @endif
    @endisset
    <div class="{{$w }}  h-auto p-2">

        <div class="">
            <label for="{{$field['id']}}" class="block font-medium tracking-tight">
                @if ($field['label'])
                    {{__($field['label'])}}
                @endif
            </label>
            @if ($field['type']=='widget')
                @if(isset($field['component']))
                    <x-dynamic-component :form="$form" :field="$field" :component="$field['component']" class="mt-4" />
                @else
                <x-back-form-widget>
                    {!! $field['html'] !!}
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
            @error($field['fieldName'])
                <span class="text-xs text-red-500">{{$message}}</span>
            @enderror
        </div>


    </div>
    @if ($field['span']=='left')
        {{-- <div></div> --}}
    @endif
    @php
        $preField = $field;

    @endphp
@endforeach

</div>



