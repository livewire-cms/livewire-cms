
@props(['field','form','widget'])

@php

@endphp
<div>


    <x-select.radio :field="$field" wire:model.lazy="{{ $field['modelName']}}" :options="$field['options']" :prefix="str_replace('-','_',$field['id'])" :inline="$field['attributes']['field']['inline']??''"></x-select.radio>

</div>
