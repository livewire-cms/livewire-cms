
@props(['field','form','widget'])


@php

@endphp
<div x-data="
init_field({
    form:@entangle('form'),
    wire:null,
    extend_init(){
        this.wire = @this
    }
})
"

x-init="init()"

>

    <input x-ref="field" type="hidden" value="{{json_encode($field)}}">
    <x-input x-bind:disabled="trigger_endable_or_disable()" wire:model="{{ $field['modelName']}}"  :id="$field['id']" :icon="$field['attributes']['field']['icon']??''"  :right-icon="$field['attributes']['field']['right-icon']??''"  :placeholder="$field['placeholder']" :hint="$field['comment']?__($field['comment']):''" :prefix="$field['attributes']['field']['prefix']??''" :suffix="$field['attributes']['field']['suffix']??''" :class="$field['cssClass'].' disabled:bg-gray-200'"/>
</div>
