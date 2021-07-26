
@props(['field','form','widget'])

@php

@endphp
<div>

    <x-form.datetimepicker wire:model.lazy="{{ $field['modelName']}}" :field="$field"></x-form.datetimepicker>


</div>
