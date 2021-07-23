
@props(['field','form','widget'])

@php

@endphp
<div>

    <x-form.datepicker wire:model.lazy="{{ $field['modelName']}}" ></x-form.datepicker>


</div>
