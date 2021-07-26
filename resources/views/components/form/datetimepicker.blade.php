



<div x-data="
init_field({
    form:@entangle('form'),
    extend_init:function(){
        flatpickr(this.$refs.input1,{
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
        })
    }
})
"
x-init="init()"
>

    <input x-ref="field" type="hidden" value="{{json_encode($attributes->get('field',[]))}}">

    <input
    x-ref="input1"
    type="text"
    :disabled="trigger_endable_or_disable()"
    class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2 disabled:bg-gray-200"
    {{$attributes->wire('model')}}

>
</div>






