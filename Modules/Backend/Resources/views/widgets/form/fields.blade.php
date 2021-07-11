{{--
@php

    $fields = $attributes->get('fields',[]);
    //todo if $field['type']=='widget' todo form makeFormFieldWidget
    // dd($fields,$tabs,$secondTabs);
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

         @endphp

        <div class="{{$w}}  h-auto ">
            @if ($field['type']=='widget')
                //todo 可以适配个动态组件
                <x-back-widget  :field="$field">
                    {!! $field['html'] !!}
                </x-back-widget>
            @else
            <div class="space-y-2">
                <label
                for="{{$field['id']}}"
                class="block font-medium tracking-tight"
                >{{$field['label']}}</label
                >
                <input
                id="{{$field['id']}}"
                wire:model="{{ $field['modelName']}}"
                class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
                placeholder="{{$field['placeholder']}}"
                />
                <span x-show="false" class="text-xs text-red-500"
                >Email is required</span>
            </div>
            @endif

        </div>

@endforeach

 --}}


 <div class="w-full  h-auto my-4">
    <ul class="flex">
    @foreach ($tabs as $tab=>$tabFields)
        <li class="cursor-pointer py-2 px-4 text-gray-500 border-b-8"
            :class="tab==='{{$tab}}' ? 'text-green-500 border-green-500' : ''" @click="tab = '{{$tab}}'"
            >{{$tab}}</li>
    @endforeach
    </ul>
    <div class="w-full mx-auto border p-9">
        @foreach ($tabs as $tab=>$tabFields)
            @foreach ($tabFields as $field)

            <div x-show="tab==='{{$tab}}'">
                @php

                    if($field['span']=='full'){
                        $w = 'w-full';
                    }elseif ($field['span']=='left') {
                        $w = 'w-1/2';
                    }elseif ($field['span']=='right') {
                        $w = 'w-1/2';
                    }
                @endphp

                <div class="{{$w}}  h-auto ">
                    @if ($field['type']=='checkboxlist')

                        <x-select.multiple wire:model="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.multiple>
                    @else
                    <div class="space-y-2">
                        <label
                        for="{{$field['id']}}"
                        class="block font-medium tracking-tight"
                        >{{$field['label']}}</label
                        >
                        <input
                        id="{{$field['id']}}"
                        wire:model="{{ $field['modelName']}}"
                        class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
                        placeholder="{{$field['placeholder']}}"
                        />
                        <span x-show="false" x-show="false" class="text-xs text-red-500"
                        >Email is required</span
                        >
                    </div>
                    @endif

                </div>

            </div>
            @endforeach
        @endforeach

    </div>

</div>


@php

                    if($field['span']=='full'){
                        $w = 'w-full';
                    }elseif ($field['span']=='left') {
                        $w = 'w-1/2';
                    }elseif ($field['span']=='right') {
                        $w = 'w-1/2';
                    }
                @endphp

                <div class="{{$w}}  h-auto ">
                    @if ($field['type']=='checkboxlist')

                        <x-select.multiple wire:model="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.multiple>
                    @else
                    <div class="space-y-2">
                        <label
                        for="{{$field['id']}}"
                        class="block font-medium tracking-tight"
                        >{{$field['label']}}</label
                        >
                        <input
                        id="{{$field['id']}}"
                        wire:model="{{ $field['modelName']}}"
                        class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
                        placeholder="{{$field['placeholder']}}"
                        />
                        <span x-show="false" x-show="false" class="text-xs text-red-500"
                        >Email is required</span
                        >
                    </div>
                    @endif

                </div>
