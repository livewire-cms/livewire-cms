
@props(['field','form','widget'])


@php

@endphp
<div>

    <x-form.check-box :field="$field" wire:model.lazy="{{ $field['modelName']}}"  :options="$field['options']" :prefix="str_replace('-','_',$field['id'])" :inline="$field['attributes']['field']['inline']??''"></x-form.check-box>


</div>
