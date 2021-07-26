
@props(['field','form','widget'])


@php

@endphp
<div  x-data="
init_field({form:@entangle('form')})
"

x-init="init()">
    <input x-ref="field" type="hidden" value="{{json_encode($field)}}">

    <x-toggle  x-bind:disabled="trigger_endable_or_disable()" lg  class="disabled:bg-gray-200" wire:model="{{$field['modelName']}}" :label="$field['label']?__($field['label']):''"/>

</div>
