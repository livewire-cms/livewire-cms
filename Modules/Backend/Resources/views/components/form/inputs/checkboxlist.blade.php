
@props(['field','form','widget'])

@php

@endphp
<div>

    <x-select.multiple :field="$field" wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])"></x-select.multiple>


</div>
