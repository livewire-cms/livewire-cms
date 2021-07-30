
@php
$fields = $attributes->get('fields',[]);
$form = $attributes->get('form',[]);
$widget = $attributes->get('widget',null);
$relation_field = $attributes->get('relation_field',null);
// dd($widget);
@endphp

<div class="flex flex-wrap" >
@foreach ($fields as $k=>$field)
    @php
        $field['relation_field'] = $relation_field;
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

    }elseif ($field['span']=='middle') {
        $w = 'w-1/2';
        $grid = 'grid-cols-2';

    }
     @endphp

    @if(isset($preField))
        @if($field['span']=='right')
            @if ($preField['span']=='full')
                <div class="w-1/2"></div>
            @endif
        @elseif($field['span']=='left')
            @if ($preField['span']=='left')
                <div class="w-full"></div>
            @endif
        @elseif($field['span']=='middle')
            <div class="w-full"></div>
            <div class="w-1/4"></div>

        @endif
    @else
        @if($field['span']=='right')
            <div class="w-full"></div>
            <div class="w-1/2"></div>
        @elseif($field['span']=='middle')
            <div class="w-full"></div>
            <div class="w-1/4"></div>
        @endif
    @endisset
    {{-- {{dd($field)}} --}}
    <div class="{{$w }}  h-auto p-2 " x-data="init_field({
        field:{},
        form: @entangle('form'),

    })" x-init="
    init()
    ">
    @if ($field['update'])
        <form key="{{\Str::random(10)}}" class="dddd">
    @endif
        <input x-ref="field" type="hidden" class="hidden" value="{{json_encode($field)}}">
        <div  x-show="trigger_show_or_hide()" class=" @if ($field['hidden']) hidden @endif">
            <label for="{{$field['id']}}" class="block font-medium tracking-tight dark:text-gray-400">
                @if ($field['label'])
                    {{__($field['label'])}}
                @endif
            </label>
            @if ($field['type']=='widget')

                @if (isset($field['html']))
                    <div wire:ignore>
                        {!! $field['html']??'' !!}
                    </div>
                @elseif($field['livewireComponent'])

                    <div wire:ignore>
                        @isset($widget)
                            @livewire($field['livewireComponent'],['field'=>$field,'widget'=>$widget,'form'=>$form,'relation_field'=>$relation_field],key($field['id']))
                        @endisset
                    </div>

                @elseif($field['component'])
                    <x-dynamic-component :widget="$widget" :form="$form" :field="$field" :component="$field['component']" class="mt-4" />
                @else
                <x-back-form-widget>
                    <div>
                        {!! $field['html']??'' !!}
                    </div>
                </x-back-form-widget>
                @endif
            @elseif(isset($field['html']))
                <div wire:ignore>
                    {!! $field['html']??'' !!}
                </div>
            @elseif($field['livewireComponent'])
                <div wire:ignore>

                    @isset($widget)

                        @livewire($field['livewireComponent'],['field'=>$field,'widget'=>$widget,'form'=>$form,'relation_field'=>$relation_field],key($field['id']))

                    @else


                    @endisset
                </div>
            @elseif ($field['component'])

                <x-dynamic-component  :form="$form" :field="$field" :component="$field['component']" :widget="$widget" class="mt-4" />



                {{-- 下面进不去 --}}
            @elseif ($field['type']=='partial')
                <x-back-form-widget>
                    {!! $field['html']??'' !!}
                </x-back-form-widget>
            @elseif ($field['type']=='checkboxlist')

                <x-select.multiple wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.multiple>

            @elseif ($field['type']=='radio')

                <x-select.radio wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])" :inline="$field['attributes']['field']['inline']??''"></x-select.radio>
            @elseif ($field['type']=='checkbox')
                <x-form.check-box wire:model.lazy="{{ $field['modelName']}}"  :options="$field['options']" :prefix="str_replace('-','_',$field['id'])" :inline="$field['attributes']['field']['inline']??''"></x-form.check-box>
            @elseif ($field['type']=='toggle')
                {{-- {{dd($field)}} --}}
                {{-- <x-form.check-box wire:model.lazy="{{ $field['modelName']}}"  :prefix="str_replace('-','_',$field['id'])"></x-form.check-box> --}}
                <x-toggle :id="$field['id']" lg   wire:model="{{$field['modelName']}}" :label="$field['label']?__($field['label']):''"/>
            @elseif ($field['type']=='dropdown')
                <x-select.single wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.single>
            @elseif ($field['type']=='datepicker')
                <x-form.datepicker wire:model.lazy="{{ $field['modelName']}}" ></x-form.datepicker>
            @elseif ($field['type']=='datetimepicker')
                <x-form.datetimepicker wire:model.lazy="{{ $field['modelName']}}" ></x-form.datetimepicker>
            @elseif ($field['type']=='editor')
                <x-form.editor wire:model.debounce.1000ms="{{ $field['modelName']}}"></x-form.editor>
            @elseif ($field['type']=='wangeditor')
                <x-form.wangeditor wire:model.lazy="{{ $field['modelName']}}" :value="$field['value']"></x-form.wangeditor>
           @elseif ($field['type']=='quilleditor')
                <x-form.quill-editor wire:model.lazy="{{ $field['modelName']}}" :value="$field['value']" :config="$field['config']['config']" :prefix="str_replace('-','_',$field['id'])"></x-form.quill-editor>
           @elseif ($field['type']=='editorjs')
                <div wire:ignore>
                    @livewire('backend.livewire.widgets.form.editorjs', [
                        'editorId' => $field["modelName"],
                        'value' => $field['value'],
                        'uploadDisk' => 'public',
                        'downloadDisk' => 'public',
                        'class' => '',
                        'style' => '',
                        'readOnly' => false,
                        'placeholder' => $field['placeholder'],
                        'relation_field'=>$relation_field
                    ],key($field['id']))
                </div>


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
            @elseif ($field['type']=='textarea')

                <x-textarea wire:model="{{ $field['modelName']}}" :id="$field['id']" :icon="$field['attributes']['field']['icon']??''"  :right-icon="$field['attributes']['field']['right-icon']??''"  :placeholder="$field['placeholder']" :hint="$field['comment']" :prefix="$field['attributes']['field']['prefix']??''" :suffix="$field['attributes']['field']['suffix']??''" :class="$field['cssClass']"/>

            @elseif ($field['type']=='text')
                {{-- {{dd($field)}} --}}

                <x-input wire:model="{{ $field['modelName']}}"  :id="$field['id']" :icon="$field['attributes']['field']['icon']??''"  :right-icon="$field['attributes']['field']['right-icon']??''"  :placeholder="$field['placeholder']" :hint="$field['comment']" :prefix="$field['attributes']['field']['prefix']??''" :suffix="$field['attributes']['field']['suffix']??''" :class="$field['cssClass']"/>
            @else

                <input
                id="{{$field['id']}}"
                wire:model="{{ $field['modelName']}}"
                class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
                placeholder="{{$field['placeholder']}}"
                />

            @endif
            {{-- {{dd($field)}} --}}
            @error($field['fieldName'])

                <span class="text-xs text-red-500">{{$message}}</span>
            @enderror
            @error($field['modelName'])
                <span class="text-xs text-red-500">{{$message}}</span>
            @enderror
        </div>
        @if ($field['update'])

        </form>
        @endif

    </div>

    @php
        $preField = $field;

    @endphp
@endforeach

</div>



