
@props(['field','form','widget'])


@php

@endphp
<div >
    <x-toggle  lg   wire:model.defer="{{$field['modelName']}}" :label="$field['label']?__($field['label']):''"/>

</div>
