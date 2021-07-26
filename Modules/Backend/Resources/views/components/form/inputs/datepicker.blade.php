
@props(['field','form','widget'])

@php

@endphp
<div>

    <x-form.datepicker wire:model.lazy="{{ $field['modelName']}}" :field="$field"></x-form.datepicker>


</div>
