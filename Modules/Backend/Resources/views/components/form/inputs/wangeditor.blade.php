
@props(['field','form','widget'])


@php

@endphp
<div>

    <x-form.wangeditor wire:model.lazy="{{ $field['modelName']}}" :value="$field['value']"></x-form.wangeditor>



</div>
