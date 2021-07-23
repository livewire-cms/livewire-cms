
@props(['field','form','widget'])

@php

@endphp
<div>

    <x-form.datetimepicker wire:model.lazy="{{ $field['modelName']}}" ></x-form.datetimepicker>


</div>
