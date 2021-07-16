
@php
$fields = $attributes->get('fields',[]);
$form = $attributes->get('form',[]);
$widget = $attributes->get('widget',null);
$relation_field = $attributes->get('relation_field',null);
// dd($widget);
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
            <label for="{{$field['id']}}" class="block font-medium tracking-tight dark:text-gray-400">
                @if ($field['label'])
                    {{__($field['label'])}}
                @endif
            </label>
            @if ($field['type']=='widget')

                @if(isset($field['component']))
                    <x-dynamic-component :form="$form" :field="$field" :component="$field['component']" class="mt-4" />
                @elseif(isset($field['livewire_component']))

                    {{-- {{dd($widget)}} --}}
                    <div wire:ignore>
                        @isset($widget)
                            @livewire($field['livewire_component'],['field'=>$field,'widget'=>$widget,'form'=>$form,'relation_field'=>$relation_field])
                        @endisset
                    </div>

                @else
                <x-back-form-widget>
                    {!! $field['html']??'' !!}
                </x-back-form-widget>
                @endisset

            @elseif ($field['type']=='partial')
                <x-back-form-widget>
                    {!! $field['html']??'' !!}
                </x-back-form-widget>
            @elseif ($field['type']=='checkboxlist')

                <x-select.multiple wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.multiple>

            @elseif ($field['type']=='radio')

                <x-select.radio wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.radio>
            @elseif ($field['type']=='checkbox')
                <x-form.check-box wire:model.lazy="{{ $field['modelName']}}"  :prefix="str_replace('-','_',$field['id'])"></x-form.check-box>

            @elseif ($field['type']=='dropdown')
                <x-select.single wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.single>
            @elseif ($field['type']=='datepicker')
                <x-form.datepicker wire:model.lazy="{{ $field['modelName']}}" ></x-form.datepicker>
            @elseif ($field['type']=='datetimepicker')
                <x-form.datetimepicker wire:model.lazy="{{ $field['modelName']}}" ></x-form.datetimepicker>
            @elseif ($field['type']=='editor')
                <x-form.editor wire:model.debounce.1000ms="{{ $field['modelName']}}"></x-form.editor>
            @elseif ($field['type']=='wangeditor')
                <x-form.wangeditor wire:model.defer="{{ $field['modelName']}}" :value="$field['value']"></x-form.wangeditor>
           @elseif ($field['type']=='quilleditor')
                <x-form.quill-editor wire:model.defer="{{ $field['modelName']}}" :value="$field['value']" :config="$field['config']['config']" :prefix="str_replace('-','_',$field['id'])"></x-form.quill-editor>
           @elseif ($field['type']=='editorjs')
                @livewire('backend.widgets.form.editorjs', [
                    'editorId' => $field["modelName"],
                    'value' => $field['value'],
                    'uploadDisk' => 'public',
                    'downloadDisk' => 'public',
                    'class' => '',
                    'style' => '',
                    'readOnly' => false,
                    'placeholder' => $field['placeholder'],
                    'relation_field'=>$relation_field
                ])

            @elseif ($field['type']=='password')

                <input
                type="password"
                id="{{$field['id']}}"
                wire:model.lazy="{{ $field['modelName']}}"
                class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
                placeholder="{{$field['placeholder']}}"
                />

            @elseif (\Str::startsWith($field['type'], 'relation_'))
               <div wire:ignore>



                   @isset($widget)
                    <x-back-form-relation_lists :field="$field" :widget="$widget" :form="$form"></x-back-form-relation_lists>
                   @endisset
               </div>
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
                <span class="text-xs text-red-500">{{$message}}</span>
            @enderror
        </div>


    </div>

    @php
        $preField = $field;

    @endphp
@endforeach

</div>



