
@php
    $id = 'id'.\Str::random(20);
    $prefix = $attributes->get('prefix')??'radio';
    $options = $attributes->get('options',[]);
    $o = [];
    foreach ($options as $key => $v) {
        $o[]=[
            'id' =>$key,
            'option' =>is_string($v)? array($v):$v
        ];
    }
    $inline = $attributes->get('inline',false);

@endphp

<div x-data="init_field({
    form:@entangle('form'),
    wire:null,
    extend_init(){
        this.wire = @this
    },
    options: [],
    value:@entangle($attributes->wire('model'))
})
"
x-init="init()"

class="bg-gray-200">
    <div class="flex flex-col  ml-5">
        <input x-ref="field" type="hidden" value="{{json_encode($attributes->get('field',[]))}}">

        <div class="flex {{$inline?'':'flex-col'}}">
            @foreach ($o as $ov)

                <label class="inline-flex items-center my-3 " >
                    <input x-bind:disabled="trigger_endable_or_disable()" type="radio"  x-model="value" class="form-radio h-5 w-5 text-green-600 disabled:bg-gray-200" value="{{$ov['id']}}"><span class="ml-2 text-gray-700">{{$ov['option'][0]}}</span>
                </label>
                @isset($ov['option'][1])
                     <p class="text-xs">{{__($ov['option'][1])}}</p>
                @endisset

            @endforeach


        </div>
    </div>
</div>


