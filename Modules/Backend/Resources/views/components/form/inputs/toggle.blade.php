
@props(['field','form','widget'])


@php

@endphp
<div>
    <x-toggle :id="$field['id']" lg   wire:model.defer="{{$field['modelName']}}" :label="$field['label']?__($field['label']):''"/>

</div>
