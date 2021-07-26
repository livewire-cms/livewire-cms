
@props(['field','form','widget'])

@php

@endphp
<div>

    <x-select.single :field="$field" wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.single>


</div>
